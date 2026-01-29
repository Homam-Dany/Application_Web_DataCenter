<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    /**
     * Handle the chat request with rule-based logic.
     */
    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $input = strtolower($request->input('message'));
        $response = $this->findResponse($input);

        return response()->json([
            'success' => true,
            'message' => $response
        ]);
    }

    private function findResponse($input)
    {
        // Base de connaissances (Mots-clés => Réponse)
        $knowledgeBase = [
            // --- SALUTATIONS ---
            [
                'keywords' => ['bonjour', 'salut', 'hello', 'coucou', 'yo'],
                'answer' => "Bonjour ! Je suis l'assistant virtuel du DataCenter. Posez-moi vos questions sur les comptes, les réservations ou les règles."
            ],

            [
                'keywords' => ['ca va', 'ça va', 'comment allez-vous'],
                'answer' => "Tout fonctionne parfaitement, merci !"
            ],

            // --- COMPTES UTILISATEURS ---
            [
                'keywords' => ['ouvrir compte', 'créer compte', 'inscription', 's\'inscrire', 'nouveau compte'],
                'answer' => "Pour ouvrir un compte : Cliquez sur 'S'inscrire' en haut à droite. Remplissez le formulaire. Votre compte sera d'abord en statut 'Invité' et devra être validé par un Administrateur pour devenir actif."
            ],

            [
                'keywords' => ['fermer compte', 'supprimer compte', 'désinscription', 'partir'],
                'answer' => "La fermeture de compte n'est pas automatique pour des raisons de sécurité. Vous devez contacter un Administrateur pour demander la désactivation ou la suppression définitive de vos données."
            ],

            [
                'keywords' => ['mot de passe', 'perdu', 'oublié'],
                'answer' => "Si vous avez oublié votre mot de passe, contactez l'administrateur système pour qu'il réinitialise vos accès."
            ],

            // --- RÉSERVATIONS & RÈGLES ---
            [
                'keywords' => ['condition', 'règle', 'règlement', 'politique'],
                'answer' => "Les règles principales : 1. Tout incident doit être signalé. 2. Les ressources doivent être libérées à la date de fin. 3. L'accès physique aux serveurs nécessite une autorisation spéciale."
            ],

            [
                'keywords' => ['durée', 'temps', 'combien de temps', 'limite'],
                'answer' => "La durée maximale d'une réservation standard est de 30 jours. Pour des projets plus longs, une demande spéciale doit être adressée au Responsable Technique."
            ],

            [
                'keywords' => ['conflit', 'déjà réservé', 'indisponible'],
                'answer' => "Si une ressource est indiquée 'Occupée' ou 'Maintenance', vous ne pouvez pas la réserver. Consultez le calendrier dans le Catalogue pour voir les prochaines disponibilités."
            ],

            // --- RÔLES ---
            [
                'keywords' => ['admin', 'administrateur', 'sysadmin'],
                'answer' => "L'Administrateur a tous les pouvoirs : il active les comptes, définit les rôles, et peut révoquer les accès. C'est le garant de la sécurité."
            ],

            [
                'keywords' => ['responsable', 'tech lead'],
                'answer' => "Le Responsable Technique gère le parc informatique. Il décide si une ressource est en maintenance et valide les demandes de réservation des ingénieurs."
            ],

            [
                'keywords' => ['invité', 'guest'],
                'answer' => "Un Invité est un utilisateur inscrit mais pas encore validé. Il a un accès en lecture seule très limité et ne peut ni réserver ni voir les détails sensibles."
            ],

            // --- FONCTIONNALITÉS ---
            [
                'keywords' => ['réserver', 'reservation', 'booking'],
                'answer' => "Allez dans 'Catalogue', choisissez un équipement libre, et cliquez sur 'Réserver'. Votre demande passera en statut 'En attente' jusqu'à validation."
            ],

            [
                'keywords' => ['incident', 'panne', 'problème', 'bug'],
                'answer' => "Un serveur fume ? Un switch ne clignote plus ? Allez vite dans 'Incidents' -> 'Signaler un incident'. Décrivez le problème pour prévenir l'équipe technique."
            ],

            [
                'keywords' => ['catalogue', 'stock', 'liste'],
                'answer' => "Le Catalogue est l'inventaire complet. Vous y voyez les serveurs, routeurs, onduleurs, leur état (Actif/Maintenance) et leur disponibilité."
            ],

            // --- DIVERS ---
            [
                'keywords' => ['technologie', 'stack'],
                'answer' => "Application développée sous Laravel (PHP). Base de données MySQL. Interface Blade et Vanilla CSS/JS."
            ],

            [
                'keywords' => ['contact', 'support', 'mail', 'téléphone'],
                'answer' => "Support Technique : support@datacenter-uae.ma | Administrateur : admin@datacenter-uae.ma"
            ],
        ];

        // Algorithme de recherche amélioré (match partiel)
        foreach ($knowledgeBase as $entry) {
            foreach ($entry['keywords'] as $keyword) {
                // Si le mot clé est trouvé dans la phrase
                if (stripos($input, $keyword) !== false) {
                    return $entry['answer'];
                }
            }
        }

        // Réponse par défaut plus complète
        return "Je n'ai pas la réponse à cette question précise. Je peux vous renseigner sur :
        - L'ouverture/fermeture de compte
        - Les règles de réservation
        - Le signalement d'incidents
        - Les rôles (Admin, Responsable...)
        
        Essayez avec des mots simples comme 'compte', 'réserver' ou 'règle'.";
    }
}
