<?php
namespace App\Controllers;

use App\Models\PlanningManager;

class PlanningController
{
    private $manager;

    public function __construct()
    {
        $this->manager = new PlanningManager();
    }

    public function planning()
    {
        require VIEWS . 'App/planning.php';
    }

    public function viewPlanning($id_release)
    {
        // 1) Récupérer la release
        $data = $this->manager->getReleaseById($id_release);
        if (!$data) {
            header('Location: /dashboard'); // id invalide => redirige
            exit;
        }

        // 2) Vérifier si planning déjà en DB
        $planningRow = $this->manager->getPlanningByReleaseId($id_release);
        $planning = null;

        if ($planningRow && isset($planningRow['content'])) {
            $planning = $planningRow['content'];
        } else {
            // Préparer le prompt (utilise les valeurs brutes, pas htmlspecialchars)
            $artiste = $data['username'] ?? '';
            $nomProjet = $data['title'] ?? '';
            $typeProjet = $data['id_type'] ?? '';
            $nbMorceaux = $data['nb_tracks'] ?? ''; // adapte si pas de champ
            $style = $data['style'] ?? '';
            $dateSortie = $data['release_date'] ?? '';
            $budget = $data['budget'] ?? '';
            $details = $data['details'] ?? '';

            $prompt = <<<EOT
Génère un planning promotionnel détaillé en Markdown pour un projet musicale.

Données du projet :
- Artiste : $artiste
- Nom du projet : "$nomProjet"
- Type de projet : $typeProjet
- Nombre de morceaux : $nbMorceaux
- Style musical : $style
- Date de sortie : $dateSortie
- Budget maximum : $budget €
- Détails artistiques fournis : "$details"

Contraintes :
1. La promotion commence 1 mois avant la sortie.
2. Elle doit se poursuivre 1 à 2 semaines après la sortie.
3. Le planning doit être chronologique (jour par jour ou semaine par semaine).
4. Inclure des actions concrètes et réalistes adaptées au style (réseaux sociaux, clips, etc.). Celons le budget tu peux inclure d'autres actions (partenariats, événements, etc.).
5. Ajouter des idées créatives et originales adaptées au style et à l’image artistique.
6. Respecter le budget indiqué.
7. Formater la réponse en **Markdown clair et lisible**, avec titres, sous-titres et listes.

Objectif :
Fournir un guide que l’artiste peut suivre étape par étape pour maximiser l’impact de la sortie.
EOT;

            try {
                $generated = $this->generatePlanningWithAI($prompt);
                if ($generated) {
                    $this->manager->insertPlanning($id_release, $generated);
                    $planning = $generated;
                } else {
                    $planning = "Erreur : l'IA n'a pas renvoyé de contenu.";
                }
            } catch (\Exception $e) {
                $planning = "Erreur lors de la génération du planning : " . $e->getMessage();
            }
        }

        // 3) Passer $data et $planning à la vue
        require VIEWS . 'App/planning.php';
    }

    public function generatePlanningWithAI($prompt)
    {
        // récupère la clé depuis plusieurs sources
        $apiKey = getenv('OPENAI_API_KEY')
               ?: ($_ENV['OPENAI_API_KEY'] ?? $_SERVER['OPENAI_API_KEY'] ?? null);

        if (!$apiKey) {
            $sources = [
                'getenv' => getenv('OPENAI_API_KEY'),
                '_ENV' => $_ENV['OPENAI_API_KEY'] ?? null,
                '_SERVER' => $_SERVER['OPENAI_API_KEY'] ?? null,
            ];
            throw new \Exception('OPENAI_API_KEY introuvable (vérifie .env et que Dotenv est chargé dans public/index.php). Sources: ' . json_encode($sources));
        }

        $url = "https://openrouter.ai/api/v1/chat/completions";

        $body = [
            "model" => "mistralai/mistral-7b-instruct",
            "messages" => [
                [
                    "role" => "user",
                    "content" => $prompt
                ]
            ],
            // Optionnel : ajuster si tu veux un résultat plus long/contrôlable
            "temperature" => 0.7,
            "max_tokens" => 1500
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new \Exception('Erreur cURL : ' . $err);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);
        if ($httpCode < 200 || $httpCode >= 300) {
            $message = $result['message'] ?? $response;
            throw new \Exception("API erreur HTTP {$httpCode} : {$message}");
        }
        if (!$result) {
            throw new \Exception('Réponse JSON invalide : ' . substr($response, 0, 300));
        }

        // Extraire le contenu IA (plusieurs formats possibles selon l'API)
        $planning = null;
        if (!empty($result['choices'][0]['message']['content'])) {
            $planning = $result['choices'][0]['message']['content'];
        } elseif (!empty($result['choices'][0]['text'])) {
            $planning = $result['choices'][0]['text'];
        } elseif (!empty($result['output'])) {
            $planning = is_string($result['output']) ? $result['output'] : json_encode($result['output']);
        }

        if (empty($planning)) {
            // Fournis la réponse brute pour debug
            throw new \Exception("Aucun contenu renvoyé par l'API. Réponse brute: " . substr($response, 0, 1000));
        }

        return $planning;
    }
}

// debug non intrusif (ne laisse pas ça en production)
$apiKey = getenv('OPENAI_API_KEY') ?: ($_ENV['OPENAI_API_KEY'] ?? $_SERVER['OPENAI_API_KEY'] ?? null);
error_log('OPENAI_API_KEY_PRESENT: ' . ($apiKey ? 'yes' : 'no'));