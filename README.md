# ai-chatbot

Petit projet Symfony fournissant une interface de chat et un service `GeminiService` pour interroger une API de génération de texte (Google Gemini ou équivalent).

## Description

Cette application Symfony 7 expose une page de chat (route `/`) et une API (préfixe `/api`) qui utilise un service `GeminiService` pour générer du texte via une API externe. Le service lit deux variables d'environnement : `GEMINI_API_KEY` et `GEMINI_API_URL`.

## Pré-requis

- PHP >= 8.2
- Composer
- (Optionnel mais recommandé) Symfony CLI (`symfony`) pour démarrer le serveur de développement
- Accès internet pour appeler l'API Gemini

## Cloner le dépôt

Remplacez l'URL par celle de votre dépôt :

git clone git@github.com:mon-org/ai-chatbot.git
cd ai-chatbot

## Variables d'environnement

Le projet utilise le mécanisme `.env` de Symfony. Ne poussez jamais vos secrets sur le dépôt public.

1. Copier le fichier d'exemple `.env` en local :

cp .env .env.local

2. Éditer `.env.local` et définir au minimum :

- `APP_ENV` (par ex. `dev` ou `prod`)
- `APP_SECRET` (une chaîne aléatoire)
- `GEMINI_API_KEY` (votre clé API pour Gemini / Generative Language)
- `GEMINI_API_URL` (URL de l'endpoint de génération)

Exemple (à adapter — valeurs factices) :

# .env.local (exemple)
APP_ENV=dev
APP_SECRET=une_valeur_secrete_a_remplacer
GEMINI_API_KEY=VOTRE_CLE_GEMINI_ICI
GEMINI_API_URL=https://generativelanguage.googleapis.com/v1beta/models/gemini-3-flash-preview:generateContent

Remarques :
- Le dépôt peut contenir un fichier `.env.local` d'exemple ; vérifiez et remplacez les valeurs par des placeholders si vous publiez le dépôt.
- `.env.local` doit rester local et ne pas être commité dans le contrôle de version.

## Installation des dépendances

Installer les dépendances PHP avec Composer :

composer install

Si vous utilisez des environnements de production, vous pouvez compiler les fichiers `.env` :

composer dump-env prod

Et vider le cache si nécessaire :

php bin/console cache:clear --env=prod

## Lancer le serveur en développement

Avec Symfony CLI (recommandé si installé) :

symfony server:start

Alternative (PHP intégré) :

php -S 127.0.0.1:8000 -t public/

Le serveur sera alors accessible sur `http://127.0.0.1:8000` (ou l'URL indiquée par la CLI Symfony).

## Tester l'application depuis un navigateur

- Page de chat (interface utilisateur) :
  - Ouvrez `http://127.0.0.1:8000/` dans votre navigateur.
- Test de connexion à Gemini (endpoint API) :
  - Ouvrez `http://127.0.0.1:8000/api/chat/test`
  - Méthode : GET
  - Réponse JSON attendue en cas de succès :

    {
      "status": "connected",
      "message": "Connexion à Gemini réussie !"
    }

  - En cas d'erreur (par ex. clé manquante ou problème réseau) :

    {
      "status": "error",
      "message": "Erreur de connexion à Gemini"
    }

Vous pouvez aussi appeler l'API principale `/api/chat` en POST depuis un client HTTP (fetch, Postman) ; consultez le code dans `src/Controller/Api/ChatController.php` pour les détails d'implémentation.

## 🚀 Demo Live

🌐 **[Voir la démo](https://ai-chatbot-untd.onrender.com)**

**NB** : *La démo est hébergée sur Render.com, avec un plan gratuit. Le serveur redémarre s'il n'y a pas d'activité durant 15min. Avec cette limitation, la première requête après une période d'inactivité peut prendre plus de temps à répondre (démarrage du serveur). Merci de votre compréhension !*

## Sécurité

- Ne commitez jamais vos clés (`GEMINI_API_KEY`) ni vos secrets (`APP_SECRET`) dans le dépôt.
- Stockez les clés en variables d'environnement sur vos serveurs de production ou utilisez un gestionnaire de secrets.
- Restreignez l'usage de la clé côté provider (si possible) et surveillez les usages.

## Liens utiles

- Symfony : https://symfony.com/
- Doctrine / Twig : consultez composer.json pour les dépendances présentes
- API Générative (exemple Gemini / Generative Language) : https://cloud.google.com/generative-ai

----

Si vous souhaitez, je peux :
- Ajouter un fichier `.env.local.example` sans secrets et l'ajouter au repo.
- Ajouter une section « Déploiement » pour Docker / CI.
- Rédiger des instructions pour tester automatiquement l'API via curl ou PHPUnit.

