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

    public function viewPlanning($id_release) {
        $data = $this->manager->getReleaseById($id_release);
        if (!$data) {
            header('Location: /dashboard');
            exit;
        }

        // Vérifie si un planning existe déjà en BDD
        $planningData = $this->manager->getPlanningByRelease($id_release);

        if (!$planningData) {
            // Appelle de l'IA
            set_time_limit(180); // Augmente le temps d'exécution pour les appels à l'IA afin d'éviter les timeouts
            $aiContent = $this->callOpenAI($data);

            // Nettoyage au cas où l'IA renvoie des backticks markdown
            $aiContent = preg_replace('/^```json\s*|\s*```$/s', '', trim($aiContent));

            $planningData = json_decode($aiContent, true) ?? [];

            // Sauvegarde en BDD pour les prochaines visites
            $this->manager->savePlanning($id_release, json_encode($planningData));
        }

        require VIEWS . 'App/planning.php';
    }

    private function callOpenAI($data) {
        // Récupère la clé API depuis les variables d'environnement
        $apiKey = getenv('OPENAI_API_KEY');

        // Si la clé n'est pas trouvée dans les variables d'environnement, essaye de la charger depuis un fichier .env
        if (!$apiKey) {
            $envPath = dirname(__DIR__, 2) . '/.env';
            if (file_exists($envPath)) {
                $env = parse_ini_file($envPath, false, INI_SCANNER_RAW);
                $apiKey = $env['OPENAI_API_KEY'] ?? null;
            }
        }

        $prompt = "
            Tu es un expert en marketing musical indépendant avec une connaissance approfondie des meilleures pratiques actuelles de l'industrie.
            Génère un planning promotionnel ultra-détaillé et professionnel pour un projet musical.

            Données du projet :
            Nom de l'artiste : {$data['username']}
            Nom du projet : {$data['title']}
            Type de projet : {$data['id_type']}
            Nombre de morceaux : {$data['number_songs']}
            Style musical : {$data['style']}
            Date de sortie : {$data['release_date']}
            Budget maximum : {$data['budget']} €
            Détails : {$data['details']}
            Date de remplissage du formulaire : {$data['created_at']}

            ---

            RECHERCHE PRÉALABLE OBLIGATOIRE — EXÉCUTE AVANT DE GÉNÉRER LE PLANNING :

            Avant de générer le planning, effectue les recherches internet suivantes pour enrichir tes recommandations avec des ressources réelles, actives et fiables :

            1. Recherche les blogs musicaux francophones et internationaux actifs spécialisés dans le genre \"{$data['style']}\" qui acceptent les soumissions d'artistes indépendants. Privilégie des sources fiables : sites de médias reconnus, annuaires officiels de blogs musicaux, articles de référence sur le marketing musical indépendant.

            2. Recherche les curateurs de playlists Spotify actifs et reconnus dans le genre \"{$data['style']}\" (via des sources comme Spotify for Artists blog, SubmitHub, Groover blog, ou articles spécialisés sur la promotion musicale indépendante).

            3. Recherche les radios et webradios francophones ou internationales adaptées au genre \"{$data['style']}\" qui programment des artistes indépendants.

            4. Recherche les techniques de marketing musical les plus efficaces et récentes pour le genre \"{$data['style']}\" sur des sources fiables (Music Business Worldwide, Hypebot, Digital Music News, MusicTech, le blog Spotify for Artists, le blog Deezer for Creators).

            5. Recherche les tarifs réels et actuels du marché pour chaque type d'action payante pertinente pour ce projet :
            - Coût moyen d'une campagne Facebook/Instagram Ads pour la promotion musicale
            - Coût moyen d'une campagne TikTok Ads / Spark Ads
            - Coût moyen d'une campagne Spotify Ad Studio
            - Coût moyen d'un clip vidéo professionnel (selon le niveau de production)
            - Coût moyen d'une campagne Groover ou plateforme de pitch presse
            - Coût moyen d'une campagne YouTube Ads pour la promotion d'un clip
            Utilise uniquement des sources fiables et récentes : blogs marketing officiels (Meta Business, TikTok for Business, Spotify Ad Studio), articles spécialisés en marketing musical (Hypebot, Music Business Worldwide, Digital Music News), ou études de cas récentes. Ces tarifs serviront de base pour estimer le champ \"cout\" de chaque action payante dans le planning.

            N'utilise QUE des sources fiables et reconnues dans l'industrie musicale. Intègre les résultats de ces recherches directement dans les actions du planning (noms de blogs réels à contacter, noms de curateurs réels, noms de radios réelles, techniques récentes adaptées au genre, tarifs réels du marché).

            ---

            RÈGLES ABSOLUES SUR LE BUDGET — EXÉCUTE CES ÉTAPES DANS L'ORDRE AVANT DE GÉNÉRER LE PLANNING :

            ÉTAPE 1 — Lis le budget : {$data['budget']} (valeur numérique entière en euros)
            ÉTAPE 2 — Applique le cas correspondant ci-dessous.

            CAS A — Budget = 0 :
            - Aucune action payante, aucune publicité, aucun outil payant n'est autorisé. Zéro exception.
            - Interdit : Facebook Ads, Instagram Ads, TikTok Ads, YouTube Ads, Spotify Ad Studio, tout boost ou sponsoring, Groover, attaché de presse payant, toute agence RP.
            - Autorisé : toutes les actions gratuites, y compris le contact presse par email direct.
            - Le champ \"budget_estime\" de chaque jour = \"0 €\" sans exception.
            - Le champ \"cout\" de chaque action = \"0 €\" sans exception.

            CAS B — Budget entre 1 et 99 :
            - Tu DOIS inclure des actions payantes. Ne pas inclure d'actions payantes est une erreur.
            - Concentre les dépenses sur 1 ou 2 actions à fort impact uniquement (ex : boost Meta Ads le jour de la sortie).
            - Toutes les autres actions doivent être gratuites.
            - Le champ \"budget_estime\" des jours sans action payante = \"0 €\".
            - La somme de tous les \"budget_estime\" du planning ne doit PAS dépasser {$data['budget']} €.

            CAS C — Budget entre 100 et 499 :
            - Tu DOIS inclure des actions payantes réparties sur plusieurs jours clés.
            - Répartis intelligemment entre Meta Ads autour du jour J et éventuellement un clip low-cost.
            - La somme de tous les \"budget_estime\" du planning ne doit PAS dépasser {$data['budget']} €.

            CAS D — Budget supérieur ou égal à 500 :
            - Tu DOIS inclure des actions payantes variées : Facebook/Instagram Ads, TikTok Ads, Spotify Ad Studio, clip professionnel et/ou Groover.
            - La somme de tous les \"budget_estime\" du planning ne doit PAS dépasser {$data['budget']} €.

            RÈGLES DE CALCUL DU BUDGET_ESTIME (valables pour tous les cas) :
            - Chaque action doit avoir un champ \"cout\" indiquant son coût estimé en euros (ex: \"cout\": \"500 €\").
            - Les actions gratuites ont obligatoirement \"cout\": \"0 €\".
            - Le champ \"cout\" de chaque action payante doit être basé sur les tarifs réels du marché trouvés lors de la recherche préalable (point 5), pas sur des chiffres inventés. Si une fourchette de prix existe, utilise la valeur médiane ou basse de cette fourchette comme estimation réaliste.
            - \"budget_estime\" d'un jour = addition exacte et vérifiée des champs \"cout\" de toutes les actions de ce jour. Calcule cette somme toi-même avant de l'écrire.
            - Ne jamais écrire un \"budget_estime\" qui ne correspond pas exactement à la somme des \"cout\" du même jour.
            - Avant de finaliser le JSON, additionne tous les \"cout\" de l'ensemble du planning et vérifie que le total est strictement inférieur ou égal à {$data['budget']} €. Si ce n'est pas le cas, réduis les montants jusqu'à respecter la limite.

            ---

            LISTE DES CANAUX AUTORISÉS — Tu ne peux utiliser QUE ces valeurs dans le champ \"canal\", sans exception :
            Instagram, TikTok, YouTube, YouTube Shorts, Facebook, Twitter/X, Spotify for Artists, Deezer for Creators, Apple Music for Artists, Email, Téléphone, Site web, Presse / Médias, Radio, Streaming (toutes plateformes)

            ---

            BIBLIOTHÈQUE DE TECHNIQUES DISPONIBLES — Utilise celles qui sont adaptées au budget, au style et à la situation de l'artiste.
            Lorsque tu rédiges les actions, utilise un langage simple, direct et accessible. Évite les références à des outils spécifiques (pas de \"Google Sheet\", \"Notion\", \"Trello\"...). Décris l'action concrète à faire, pas l'outil pour la faire.

            ### PRÉPARATION MARKETING (avant le lancement de la promotion)
            - Préparer l'artwork (bannières \"Date de sortie\" + \"Disponible maintenant\" + pochette)
            - Préparer le Canvas Spotify (vidéo verticale 9:16, 3-8 secondes en boucle, sans texte ni logo)
            - Mettre à jour les profils réseaux sociaux : bio, photo de profil, highlights Instagram, lien en bio → smartlink
            - Mettre à jour la biographie sur toutes les plateformes (Spotify, Deezer, Apple Music, YouTube Music)
            - Créer un smartlink / Fanlink (Linkfire, Toneden, Feature.fm, Show.co) regroupant toutes les plateformes
            - Créer une campagne de lien Pre-Save pour booster les stats dès la sortie
            - Faire une liste des curateurs de playlists et blogs à contacter : noter leur nom, leur lien, leur genre musical, et leur moyen de contact
            - Préparer un dossier de presse simple : biographie courte, photos, liens de streaming, et une citation marquante si disponible

            ### PITCH PLATEFORMES & PLAYLISTS ÉDITORIALES
            - Pitcher le morceau à l'équipe éditoriale Spotify via Spotify for Artists (minimum 7 jours avant la sortie, maximum 7 semaines avant — OBLIGATOIRE pour espérer une playlist éditoriale)
            - Soumettre le morceau aux playlists éditoriales Deezer via Deezer for Creators
            - Soumettre aux playlists Apple Music via Apple Music for Artists
            - Contacter des curateurs de playlists indépendantes (Spotify, YouTube, Deezer) par email personnalisé avec un lien d'écoute privé
            - Contacter des blogs musicaux spécialisés dans le genre de l'artiste en leur envoyant le dossier de presse
            - Contacter des webzines et médias indépendants
            - Contacter des radios locales ou thématiques adaptées au genre
            - Envoyer un communiqué de presse pour annoncer la sortie
            - Contacter des journalistes musicaux spécialisés dans le genre de l'artiste
            - Demander des reviews ou interviews à des créateurs YouTube ou podcasteurs musicaux du même genre
            - Relancer les contacts qui n'ont pas répondu (5 à 10 jours après le premier contact)

            ### RÉSEAUX SOCIAUX — CONTENU ORGANIQUE
            - Instagram : publication de l'artwork officiel, stories quotidiennes J-7 à J+7, reels courts (15-30s extraits du morceau)
            - TikTok : créer un son depuis le morceau, publier des vidéos utilisant ce son (behind the scenes, réaction, processus de création, défi)
            - TikTok & Instagram : utiliser des hashtags de niche adaptés au genre. Pour trouver les plus pertinents, consulter des outils gratuits comme Hashtagify.me, Displaypurposes.com ou All-Hashtag.com
            - YouTube : publier le clip ou la lyrics video le jour de la sortie, soigner le titre, la description, les tags et la miniature
            - YouTube Shorts : republier les vidéos courtes TikTok/Reels sur YouTube Shorts pour toucher un algorithme supplémentaire
            - Facebook : publication sur la page artiste + partage dans des groupes thématiques du genre
            - Twitter/X : teaser, fil storytelling sur la création du morceau, échanges avec la communauté
            - Créer une playlist publique Spotify avec ses propres morceaux + artistes similaires plus connus (pour apparaître en \"Découvert sur\" sur leur profil)
            - Épingler la sortie en \"Artist Pick\" sur Spotify for Artists dès le jour J
            - Publier des stories avec compte à rebours à partir de J-7 (7 jours maximum avant la sortie)
            - Publier l'artwork officiel avec la date de sortie à J-14
            - Publier un extrait du morceau (15-30s) à J-7
            - Publier le 1er teaser vidéo à J-3
            - Publier le 2e teaser vidéo à J-2
            - Veille de sortie : dernier contenu de buzz, rappel de la sortie (J-1)
            - Contacts one-to-one le jour J : envoyer des messages personnels aux personnes les plus influentes (artistes partenaires, professionnels, blogueurs et curateurs ayant déjà montré de l'intérêt) pour les inciter à écouter et partager
            - Partager les retours et réactions de fans dans les stories
            - Relancer régulièrement avec du contenu frais : behind the scenes, anecdotes de création, live acoustique, session studio

            ### CONTENUS VIDÉO (adapter selon budget)
            - Budget 0 € : teaser filmé au smartphone, lumière naturelle, montage sur CapCut, InShot ou DaVinci Resolve
            - Budget faible : clip tourné dans un lieu symbolique avec un ami caméraman, montage personnel
            - Budget moyen : clip avec une petite équipe, location d'un lieu, post-production soignée
            - Budget élevé : clip professionnel avec réalisateur et équipe technique complète
            - Vidéo \"Behind the Scenes\" du processus de création (J+3 après sortie)
            - Vidéo de prestation : live, répétition ou performance en extérieur (J+2 après sortie)
            - Live Instagram ou Facebook : concert, session de prod ou mix en direct (J+4 après sortie)
            - Lyrics video sur YouTube si pas de clip disponible (J+5 après sortie)

            ### PUBLICITÉ PAYANTE (uniquement si budget > 0 €)
            - Facebook/Instagram Ads via Meta Business Suite : ciblage par genre musical, artistes similaires, âge, zone géographique
            - Lancer les ads le jour de la sortie ou J-3 avec un visuel accrocheur + lien vers la plateforme de streaming
            - Optimiser les ads à J+2, J+3, J+4 selon les performances (désactiver les moins performantes, augmenter sur les meilleures)
            - TikTok Ads : Spark Ads (booster un post organique existant) si budget disponible
            - YouTube Ads : promouvoir le clip via Google Ads
            - Spotify Ad Studio : créer une publicité audio diffusée entre les morceaux pour les auditeurs gratuits (budget minimum ~250 €)

            ### OPTIMISATION STREAMING & ANALYSE
            - Épingler le nouveau titre en \"Artist Pick\" Spotify avec un message personnalisé dès le jour J
            - Ajouter le titre à ses propres playlists publiques Spotify
            - Uploader le Canvas Spotify pour augmenter le taux d'écoute et le taux de partage
            - Suivre les statistiques (Spotify for Artists, Deezer for Creators, Apple Music for Artists) pour ajuster la stratégie en temps réel

            ### APRÈS LA SORTIE — ENTRETENIR LA DYNAMIQUE
            - J+1 : Remercier la communauté en story et en post, partager les premières stats marquantes
            - J+2 : Publier une vidéo de prestation ou une session live
            - J+3 : Publier du contenu \"Behind the Scenes\" ou raconter l'histoire derrière la création
            - J+4 : Organiser un live Instagram ou Facebook (questions/réponses, session acoustique, mix en direct)
            - J+5 : Mettre en ligne le clip sur YouTube (ou une lyrics video)
            - J+6 : Remercier individuellement les personnes qui ont soutenu la sortie, republier les retours de fans
            - Continuer à publier du contenu régulier sur les réseaux pendant 1 à 2 semaines après la sortie
            - Relancer les curateurs et blogs qui n'ont pas encore répondu

            ---

            CONTRAINTES IMPÉRATIVES :
            1. La promotion commence 1 mois avant la sortie (ou dès le lendemain du remplissage du formulaire si moins d'un mois). Garde 1 à 2 semaines pour tout préparer, puis lance la promotion active jusqu'à 1-2 semaines après la sortie.
            2. Planning chronologique, jour par jour.
            3. Actions concrètes et réalistes adaptées au style et à l'image de l'artiste.
            4. Idées créatives adaptées à l'image artistique de l'artiste.
            5. Si les détails précisent des éléments à inclure (ex : contacter tel blog, poster sur Instagram, lancer une pub Facebook), les intégrer au bon moment du planning.
            6. Si les détails précisent une date de concert, inclure des actions de promotion du concert au bon moment.
            7. Ne pas ajouter une action telle que \"Créer un planning promotionnel\".
            8. Le compte à rebours en story commence MAXIMUM 7 jours avant la sortie.
            9. Pitcher Spotify for Artists doit être fait au minimum 7 jours avant la sortie (idéalement dès le début si le temps le permet, mais pas avant 7 semaines avant la sortie).
            10. La date de sortie doit idéalement être un vendredi — si ce n'est pas le cas, le mentionner dans l'action de distribution.
            11. Toujours publier aux heures de fort engagement : Instagram/TikTok entre 18h-21h en semaine, 12h-14h le week-end. Préciser l'heure recommandée dans chaque action de publication sur les réseaux sociaux.

            RÉPONDS UNIQUEMENT avec un tableau JSON valide, sans markdown ni texte autour.
            Format de chaque objet (une entrée par jour d'action) :
            {
                \"jour\": \"10 avril 2025\",
                \"actions\": [
                    {\"action\": \"Pitcher le morceau sur Spotify for Artists pour les playlists éditoriales\", \"canal\": \"Spotify for Artists\", \"cout\": \"0 €\"},
                    {\"action\": \"Publier l'artwork officiel en story avec compte à rebours\", \"canal\": \"Instagram\", \"cout\": \"0 €\"},
                    {\"action\": \"Lancer une campagne Facebook/Instagram Ads avec ciblage genre et artistes similaires\", \"canal\": \"Facebook\", \"cout\": \"500 €\"}
                ],
                \"budget_estime\": \"500 €\"
            }
            Règle absolue : \"budget_estime\" doit toujours être égal à la somme exacte des champs \"cout\" des actions du même jour. Toute incohérence entre les \"cout\" individuels et le \"budget_estime\" est une erreur grave.
            Regroupe TOUTES les actions du même jour dans le tableau \"actions\". Une entrée par jour d'action uniquement (pas de jours sans action).
        ";

        // Appel à l'API OpenAI
        $ch = curl_init();

        // URL de l'API OpenAI
        curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");

        // Configuration de cURL pour l'appel à l'API
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Méthode POST
        curl_setopt($ch, CURLOPT_POST, true);

        // Timeouts pour éviter les blocages
        curl_setopt($ch, CURLOPT_TIMEOUT, 180);

        // Chemin vers le certificat CA pour éviter les erreurs SSL (ajuste le chemin selon ton environnement)
        // curl_setopt($ch, CURLOPT_CAINFO, "C:/wamp64/bin/php/php8.4.6/extras/ssl/cacert.pem");
        
        // Timeout pour la connexion (plus court que le timeout total pour éviter les longues attentes en cas de problème de réseau)
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        
        // Chemin vers le certificat CA pour éviter les erreurs SSL (ajuste le chemin selon ton environnement)
        // curl_setopt($ch, CURLOPT_CAINFO, "C:/wamp64/bin/php/php8.4.6/extras/ssl/cacert.pem");
        
        // En-têtes avec la clé API
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $apiKey
        ]);

        // Données à envoyer à l'API
        $apiData = [
            "model" => "gpt-4.1-mini",
            "messages" => [["role" => "user", "content" => $prompt]],
            'max_tokens' => 10000
        ];


        // Envoi de la requête
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiData));
        $response = curl_exec($ch);

        // Gestion des erreurs cURL (si l'appel échoue, affiche l'erreur et arrête le script)
        if (curl_errno($ch)) {
            die('Erreur cURL : ' . curl_error($ch));
        }


        // Ferme la session cURL
        curl_close($ch);

        
        // Décodage de la réponse JSON
        $result = json_decode($response, true);

        // Retourne le contenu généré par l'IA ou null si la structure de la réponse n'est pas celle attendue
        return $result['choices'][0]['message']['content'] ?? null;
    }
}