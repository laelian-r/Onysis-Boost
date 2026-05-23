# **Documentation : Fonction `callOpenAI` (Explication Ligne par Ligne)**

---

## **1. Récupération de la clé API**

```php
// Récupère la clé API depuis les variables d'environnement
$apiKey = getenv('OPENAI_API_KEY');
```

- **Explication** :
  - Cette ligne essaie de récupérer la clé API OpenAI depuis les variables d'environnement du serveur.
  - Si la clé n'est pas définie dans les variables d'environnement, `$apiKey` sera `null`.

---

```php
// Si la clé n'est pas trouvée dans les variables d'environnement, essaye de la charger depuis un fichier .env
if (!$apiKey) {
    $envPath = dirname(__DIR__, 2) . '/.env';
    if (file_exists($envPath)) {
        $env = parse_ini_file($envPath, false, INI_SCANNER_RAW);
        $apiKey = $env['OPENAI_API_KEY'] ?? null;
    }
}
```

- **Explication** :
  - Si `$apiKey` est vide, le code cherche un fichier `.env` à la racine du projet.
  - Il lit le fichier `.env` et extrait la valeur de `OPENAI_API_KEY`.
  - Si la clé n'est pas trouvée dans le fichier `.env`, `$apiKey` reste `null`.

---

## **2. Construction du Prompt**

```php
$prompt = "
    Tu es un expert en marketing musical.
    Génère un planning promotionnel détaillé pour un projet musical.
    Données du projet :
    Artiste : {$data['username']}
    Nom du projet : {$data['title']}
    Type de projet : {$data['id_type']}
    Nombre de morceaux : {$data['number_songs']}
    Date de sortie : {$data['release_date']}
    Budget maximum : {$data['budget']}
    Détails : {$data['details']}
    ...
";
```

- **Explication** :
  - Le `prompt` est le texte envoyé à l'API OpenAI pour lui expliquer ce qu'elle doit faire.
  - Ici, on demande à l'IA de générer un planning promotionnel pour un projet musical.
  - Les données du projet (`$data`) sont insérées directement dans le prompt pour personnaliser la réponse.

---

## **3. Initialisation de cURL**

```php
// Appel à l'API OpenAI
$ch = curl_init();
```

- **Explication** :
  - `curl_init()` initialise une nouvelle session cURL.
  - cURL est un outil qui permet d'envoyer des requêtes HTTP (comme celles utilisées pour communiquer avec l'API OpenAI).

---

## **4. Configuration de l'URL de l'API**

```php
// URL de l'API OpenAI
curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
```

- **Explication** :
  - `CURLOPT_URL` définit l'URL vers laquelle la requête sera envoyée.
  - Ici, on cible l'endpoint `chat/completions` de l'API OpenAI, qui permet de générer des réponses basées sur un prompt.

---

## **5. Configuration du retour de la requête**

```php
// Configuration de cURL pour l'appel à l'API
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
```

- **Explication** :
  - `CURLOPT_RETURNTRANSFER` indique à cURL de retourner la réponse de l'API sous forme de chaîne de caractères, plutôt que de l'afficher directement.

---

## **6. Configuration de la méthode HTTP**

```php
// Méthode POST
curl_setopt($ch, CURLOPT_POST, true);
```

- **Explication** :
  - `CURLOPT_POST` indique que la requête doit être envoyée en méthode `POST`.
  - La méthode `POST` est utilisée pour envoyer des données (comme le prompt) à l'API.

---

## **7. Configuration des timeouts**

```php
// Timeouts pour éviter les blocages
curl_setopt($ch, CURLOPT_TIMEOUT, 90);
// Timeout pour la connexion (plus court que le timeout total pour éviter les longues attentes en cas de problème de réseau)
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
```

- **Explication** :
  - `CURLOPT_TIMEOUT` : Limite le temps total de la requête à 90 secondes. Si l'API ne répond pas dans ce délai, la requête est annulée.
  - `CURLOPT_CONNECTTIMEOUT` : Limite le temps de connexion initiale à 10 secondes. Si la connexion échoue dans ce délai, la requête est annulée.

---

## **8. Configuration du certificat SSL**

```php
// Chemin vers le certificat CA pour éviter les erreurs SSL (ajuste le chemin selon ton environnement)
curl_setopt($ch, CURLOPT_CAINFO, "C:/wamp64/bin/php/php8.4.6/extras/ssl/cacert.pem");
```

- **Explication** :
  - `CURLOPT_CAINFO` spécifie le chemin vers un certificat SSL valide.
  - Ce certificat est nécessaire pour vérifier l'authenticité du serveur OpenAI et sécuriser la connexion.
  - Le chemin doit être adapté à votre environnement (par exemple, sous Linux, ce serait quelque chose comme `/etc/ssl/certs/ca-certificates.crt`).

---

## **9. Configuration des en-têtes HTTP**

```php
// En-têtes avec la clé API
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer " . $apiKey
]);
```

- **Explication** :
  - `CURLOPT_HTTPHEADER` définit les en-têtes HTTP envoyés avec la requête.
  - `Content-Type: application/json` : Indique que les données envoyées sont au format JSON.
  - `Authorization: Bearer $apiKey` : Authentifie la requête en utilisant la clé API OpenAI.

---

## **10. Préparation des données à envoyer**

```php
// Données à envoyer à l'API
$apiData = [
    "model" => "gpt-4.1-mini",
    "messages" => [["role" => "user", "content" => $prompt]]
];
```

- **Explication** :
  - `$apiData` est un tableau associatif contenant les données à envoyer à l'API OpenAI.
  - `model` : Spécifie le modèle IA à utiliser (`gpt-4.1-mini` dans cet exemple).
  - `messages` : Contient le prompt sous forme de message avec le rôle `user`.

---

## **11. Envoi des données**

```php
// Envoi de la requête
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($apiData));
```

- **Explication** :
  - `CURLOPT_POSTFIELDS` définit les données à envoyer avec la requête `POST`.
  - `json_encode($apiData)` convertit le tableau `$apiData` en une chaîne JSON, format attendu par l'API OpenAI.

---

## **12. Exécution de la requête**

```php
$response = curl_exec($ch);
```

- **Explication** :
  - `curl_exec($ch)` envoie la requête et récupère la réponse de l'API OpenAI.
  - La réponse est stockée dans la variable `$response`.

---

## **13. Gestion des erreurs cURL**

```php
// Gestion des erreurs cURL (si l'appel échoue, affiche l'erreur et arrête le script)
if (curl_errno($ch)) {
    die('Erreur cURL : ' . curl_error($ch));
}
```

- **Explication** :
  - `curl_errno($ch)` vérifie si une erreur s'est produite pendant l'exécution de la requête.
  - Si une erreur est détectée, `curl_error($ch)` retourne une description de l'erreur.
  - `die()` arrête l'exécution du script et affiche le message d'erreur.

---

## **14. Fermeture de la session cURL**

```php
// Ferme la session cURL
curl_close($ch);
```

- **Explication** :
  - `curl_close($ch)` libère les ressources utilisées par la session cURL.

---

## **15. Décodage de la réponse**

```php
// Décodage de la réponse JSON
$result = json_decode($response, true);
```

- **Explication** :
  - `json_decode($response, true)` convertit la réponse JSON en un tableau associatif PHP.
  - Le deuxième argument `true` indique que les objets JSON doivent être convertis en tableaux associatifs.

---

## **16. Retour du résultat**

```php
// Retourne le contenu généré par l'IA ou null si la structure de la réponse n'est pas celle attendue
return $result['choices'][0]['message']['content'] ?? null;
```

- **Explication** :
  - Cette ligne extrait le contenu de la réponse générée par l'IA.
  - `$result['choices'][0]['message']['content']` accède au premier choix de réponse et à son contenu.
  - L'opérateur `?? null` retourne `null` si le chemin n'existe pas dans le tableau, évitant ainsi une erreur.

---

## **Résumé des étapes**

1. Récupérer la clé API OpenAI.
2. Construire le prompt avec les données du projet.
3. Initialiser une session cURL.
4. Configurer l'URL, la méthode, les en-têtes, les timeouts et le certificat SSL.
5. Préparer et envoyer les données à l'API OpenAI.
6. Exécuter la requête et gérer les erreurs éventuelles.
7. Fermer la session cURL.
8. Décoder la réponse JSON.
9. Retourner le contenu généré par l'IA.
