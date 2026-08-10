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

    public function ask(string $message, ?string $imageBase64 = null): string
    {
        if (empty($this->apiKey)) {
            return "Désolé, la clé API de l'IA (Gemini) n'est pas configurée. Veuillez ajouter `GEMINI_API_KEY` dans votre fichier .env.";
        }

        $context = $this->buildContext();

        $systemPrompt = "Tu es l'assistant IA d'un Data Center. Tu dois répondre de façon concise, professionnelle et polie. Voici le contexte actuel de la base de données :\n" . $context . "\n\nRéponds à la question de l'utilisateur basée UNIQUEMENT sur ces données. Si la question n'a rien à voir avec le Data Center, recadre poliment la conversation. \nVoici les liens exacts (URL) de l'application que tu dois utiliser si tu dois rediriger l'utilisateur :\n- Dashboard: /dashboard\n- Mes Réservations: /mes-reservations\n- Faire une réservation: /reserver\n- Catalogue: /catalogue\n- Profil: /profile\nFournis le lien au format Markdown ainsi : [Aller vers la page](/url_exacte){: .btn .btn-primary }.\nSi l'utilisateur te demande d'écrire un fichier, de générer du code ou un document texte (rapport), réponds TOUJOURS en utilisant un bloc de code Markdown standard (```). Cela permettra à l'utilisateur de le télécharger directement via l'interface.\nSi l'utilisateur te demande de générer ou de lui montrer une photo/image (ex: 'donne moi une photo de serveur'), tu NE DOIS PAS refuser ! Utilise le format Markdown pour générer une image via le service d'IA gratuit Pollinations. Format strict : `![Description](https://image.pollinations.ai/prompt/description_en_anglais_detaillee)`. Exemple pour un réseau : `![Réseau](https://image.pollinations.ai/prompt/data%20center%20network%20servers)`.";

        $parts = [
            ['text' => $systemPrompt . "\n\nQuestion de l'utilisateur: " . $message]
        ];

        if ($imageBase64) {
            $mimeType = 'image/jpeg';
            $data = $imageBase64;
            
            if (preg_match('/^data:(image\/[a-zA-Z0-9]+);base64,(.*)$/', $imageBase64, $matches)) {
                $mimeType = $matches[1];
                $data = $matches[2];
            }

            $parts[] = [
                'inlineData' => [
                    'mimeType' => $mimeType,
                    'data' => $data
                ]
            ];
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=' . $this->apiKey, [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => $parts
                ]
            ]
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
        
        $availableResources = Resource::where('status', 'disponible')->get(['name', 'type', 'rack_position'])->toArray();
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
        }

        return $context;
    }
}
