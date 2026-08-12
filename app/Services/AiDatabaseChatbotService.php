<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Resource;
use App\Models\Incident;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

class AiDatabaseChatbotService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
    }

    public function ask(string $message, ?string $imageBase64 = null, array $history = []): string
    {
        if (empty($this->apiKey)) {
            return "Désolé, la clé API de l'IA (Gemini) n'est pas configurée. Veuillez ajouter `GEMINI_API_KEY` dans votre fichier .env.";
        }

        $context = $this->buildContext();

        $user = Auth::user();
        $reportInstruction = ($user && in_array($user->role, ['admin'])) 
            ? "Si l'utilisateur demande un rapport mensuel ou des statistiques, propose le lien suivant : [Télécharger le Rapport Mensuel](/admin/rapports/mensuel){: .btn .btn-primary}.\n"
            : "";

        $systemPrompt = "Tu es l'assistant IA d'un Data Center. Tu dois répondre de façon concise, professionnelle et polie. Voici le contexte actuel de la base de données :\n" . $context . "\n\nRéponds à la question de l'utilisateur basée UNIQUEMENT sur ces données. Si la question n'a rien à voir avec le Data Center, recadre poliment la conversation. \nVoici les liens exacts (URL) de l'application que tu dois utiliser si tu dois rediriger l'utilisateur :\n- Dashboard: /dashboard\n- Mes Réservations: /mes-reservations\n- Faire une réservation: /reserver\n- Catalogue: /catalogue\n- Profil: /profile\nFournis le lien au format Markdown ainsi : [Aller vers la page](/url_exacte){: .btn .btn-primary }.\nIMPORTANT : Si l'utilisateur demande à réserver une ressource spécifique, tu DOIS générer le lien de réservation avec le paramètre resource_id correspondant (ex: [Réserver le serveur XYZ](/reserver?resource_id=12){: .btn .btn-primary}). Trouve l'ID de la ressource dans le contexte fourni.\n" . $reportInstruction . "Si l'utilisateur demande d'annuler une réservation précise (trouve son ID dans le contexte) ou de signaler un incident, tu dois UNIQUEMENT renvoyer un bloc de code JSON structuré comme suit (sans aucun autre texte) :\n- Annuler: ```json\n{\"action\": \"cancel_reservation\", \"reservation_id\": ID}\n```\n- Signaler incident: ```json\n{\"action\": \"report_incident\", \"title\": \"Titre court\", \"description\": \"Description détaillée\"}\n```\nSi l'utilisateur te demande de générer ou de lui montrer une photo/image, tu NE DOIS PAS refuser ! Utilise le format Markdown pour générer une image via le service d'IA gratuit Pollinations. Format strict : `![Description](https://image.pollinations.ai/prompt/description)`.";

        $userMessagePart = ['text' => $systemPrompt . "\n\nQuestion de l'utilisateur: " . $message];

        $contents = [];
        
        // Ajouter l'historique
        foreach ($history as $msg) {
            $contents[] = [
                'role' => $msg['role'],
                'parts' => [['text' => $msg['content']]]
            ];
        }

        // Ajouter le message actuel de l'utilisateur avec son contexte
        if ($imageBase64) {
            $userMessagePart = ['text' => $systemPrompt . "\n\nQuestion de l'utilisateur (avec image jointe): " . $message];
            $contents[] = [
                'role' => 'user',
                'parts' => [
                    $userMessagePart,
                    [
                        'inlineData' => [
                            'mimeType' => $mimeType,
                            'data' => $data
                        ]
                    ]
                ]
            ];
        } else {
            $contents[] = [
                'role' => 'user',
                'parts' => [$userMessagePart]
            ];
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $this->apiKey, [
            'contents' => $contents
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return $data['candidates'][0]['content']['parts'][0]['text'] ?? "Je n'ai pas pu générer de réponse.";
        }

        return "Erreur lors de la communication avec l'IA : " . $response->status();
    }

    private function buildContext(): string
    {
        $user = Auth::user();
        
        $availableResources = Resource::where('status', 'disponible')->get(['id', 'name', 'type', 'rack_position'])->toArray();
        $incidents = Incident::where('status', 'ouvert')->get(['title', 'description', 'created_at'])->toArray();
        
        $context = "- Utilisateur actuel : " . ($user ? $user->name . " (Rôle: " . $user->role . ")" : "Visiteur") . "\n";
        $context .= "- Nombre total de ressources disponibles : " . count($availableResources) . "\n";
        
        // Si peu de ressources, on donne le détail complet, sinon un échantillon
        if (count($availableResources) > 0) {
            $sample = array_slice($availableResources, 0, 10);
            $context .= "  Détails (échantillon max 10) : " . json_encode($sample) . "\n";
        }
        
        $context .= "- Incidents ouverts en cours : " . count($incidents) . "\n";
        if (count($incidents) > 0) {
            $context .= "  Détails des incidents : " . json_encode($incidents) . "\n";
        }

        if ($user) {
            $myReservations = Reservation::where('user_id', $user->id)->whereIn('status', ['Approuvée', 'Active'])->get(['id', 'start_date', 'end_date'])->toArray();
            $context .= "- Réservations actives de cet utilisateur : " . count($myReservations) . "\n";
            
            if (in_array($user->role, ['admin', 'responsable'])) {
                $logPath = storage_path('logs/laravel.log');
                if (file_exists($logPath)) {
                    $fileSize = filesize($logPath);
                    $fp = fopen($logPath, 'r');
                    $pos = max(0, $fileSize - 5000); // 5 derniers Ko
                    fseek($fp, $pos);
                    $logs = fread($fp, 5000);
                    fclose($fp);
                    $context .= "\n--- [LOGS SYSTÈME RÉCENTS (POUR DIAGNOSTIC UNIQUEMENT)] ---\n" . $logs . "\n----------------------------------------------------\n";
                }
            }
        }

        // RAG : Base de Connaissances (Fichiers textes dans storage/app/kb)
        $kbPath = storage_path('app/kb');
        if (is_dir($kbPath)) {
            $kbContent = "";
            $files = glob($kbPath . '/*.{txt,md}', GLOB_BRACE);
            foreach ($files as $file) {
                $kbContent .= "\n--- Extrait du document : " . basename($file) . " ---\n";
                $kbContent .= substr(file_get_contents($file), 0, 2000); // Limite de taille par fichier
                $kbContent .= "\n";
            }
            if (!empty($kbContent)) {
                $context .= "\n--- [DOCUMENTATION TECHNIQUE (RAG)] ---\nVoici des extraits de la documentation technique du Data Center à utiliser si la question de l'utilisateur porte sur ces sujets :\n" . $kbContent . "\n----------------------------------------------------\n";
            }
        }

        return $context;
    }
}
