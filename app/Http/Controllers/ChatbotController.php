<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    // Base de connaissances structurée "Menu Telegram"
    private $knowledgeBase = [
        [
            'id' => 1,
            'question' => "📝 Comment créer un compte ?",
            'answer' => "Pour créer un compte :
1. Cliquez sur le bouton 'S'inscrire' en haut à droite.
2. Remplissez le formulaire avec votre email professionnel.
3. Votre compte sera en attente de validation par un administrateur.
4. Une fois validé, vous recevrez un email de confirmation.",
            'keywords' => ['ouvrir', 'créer', 'inscription', 'compte']
        ],
        [
            'id' => 2,
            'question' => "📅 Comment réserver une ressource ?",
            'answer' => "La procédure de réservation :
1. Connectez-vous à votre espace.
2. Allez dans le 'Catalogue'.
3. Choisissez une ressource disponible (statut Vert).
4. Cliquez sur 'Réserver' et définissez la durée.
Votre demande sera examinée par le Responsable Technique.",
            'keywords' => ['réserver', 'reservation', 'booking']
        ],
        [
            'id' => 3,
            'question' => "❓ J'ai oublié mon mot de passe",
            'answer' => "Pas de panique !
Cliquez sur 'Mot de passe oublié ?' sur la page de connexion. Entrez votre email, et nous vous enverrons un lien sécurisé pour le réinitialiser.",
            'keywords' => ['mot de passe', 'mdp', 'password', 'oublié']
        ],
        [
            'id' => 4,
            'question' => "⚠️ Signaler un incident",
            'answer' => "Si vous constatez une panne ou un problème matériel :
1. Connectez-vous.
2. Allez dans le menu 'Incidents'.
3. Cliquez sur 'Signaler'.
4. Décrivez le problème. L'équipe technique interviendra rapidement.",
            'keywords' => ['incident', 'panne', 'bug', 'problème']
        ],
        [
            'id' => 5,
            'question' => "👑 Quels sont les rôles ?",
            'answer' => "Les rôles dans l'application :
- **Invité** : Accès limité en lecture seule.
- **Utilisateur** : Peut réserver et signaler des incidents.
- **Responsable** : Gère le parc et valide les réservations.
- **Admin** : Gère les utilisateurs et la configuration globale.",
            'keywords' => ['rôle', 'droit', 'permission', 'admin']
        ],
        [
            'id' => 6,
            'question' => "📞 Contacter le support",
            'answer' => "Vous pouvez nous joindre directement :
📧 Email : support@datacenter-uae.ma
🏢 Bureau : Salle Serveur, 2ème étage, FST Tanger.",
            'keywords' => ['contact', 'mail', 'support', 'téléphone']
        ]
    ];

    /**
     * Renvoie la liste des questions pour le menu du Chatbot
     */
    public function index()
    {
        // On retourne juste les questions pour l'affichage
        $menu = array_map(function ($item) {
            return [
                'id' => $item['id'],
                'text' => $item['question']
            ];
        }, $this->knowledgeBase);

        return response()->json($menu);
    }

    /**
     * Traite la question (soit par ID de menu, soit par texte libre)
     */
    public function ask(Request $request, \App\Services\AiDatabaseChatbotService $aiService)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'image' => 'nullable|string', // Base64 image
        ]);

        $input = $request->input('message');
        $imageBase64 = $request->input('image');

        // 1. Recherche exacte (si l'utilisateur clique sur un bouton du menu)
        // Ignoré si on a une image jointe
        if (!$imageBase64) {
            foreach ($this->knowledgeBase as $item) {
                if ($item['question'] === $input) {
                    return response()->json([
                        'success' => true,
                        'message' => $item['answer']
                    ]);
                }
            }
        }

        // 2. Si ce n'est pas une question exacte du menu, on interroge l'IA avec le contexte DB (et potentiellement l'image)
        $aiResponse = $aiService->ask($input, $imageBase64);

        return response()->json([
            'success' => true,
            'message' => $aiResponse
        ]);
    }
}
