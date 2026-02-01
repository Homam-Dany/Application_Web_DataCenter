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
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $input = $request->input('message');
        $response = "Désolé, je ne comprends pas votre demande. Essayez d'utiliser le menu ci-dessous.";

        // 1. Recherche exacte (si l'utilisateur clique sur le menu)
        foreach ($this->knowledgeBase as $item) {
            if ($item['question'] === $input) {
                return response()->json([
                    'success' => true,
                    'message' => $item['answer']
                ]);
            }
        }

        // 2. Recherche par mots-clés (si l'utilisateur tape du texte)
        $inputLower = strtolower($input);
        foreach ($this->knowledgeBase as $item) {
            foreach ($item['keywords'] as $keyword) {
                if (stripos($inputLower, $keyword) !== false) {
                    return response()->json([
                        'success' => true,
                        'message' => $item['answer']
                    ]);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => $response
        ]);
    }
}
