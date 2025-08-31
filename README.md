# Payment Backend (Laravel API)

Ce projet est le backend de l'application de gestion de paiements, développé avec Laravel. Il fournit une API RESTful sécurisée avec authentification JWT pour gérer les utilisateurs, les paiements et les statistiques du tableau de bord.

## Table des Matières

- [Technologies Utilisées](#technologies-utilisées)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Configuration](#configuration)
- [Base de Données](#base-de-données)
- [Exécution du Serveur](#exécution-du-serveur)
- [Endpoints API](#endpoints-api)
- [Tests](#tests)
- [Déploiement](#déploiement)
- [Structure du Projet](#structure-du-projet)

## Technologies Utilisées

- **PHP**: ^8.1
- **Laravel**: ^10.10
- **Tymon/JWT-Auth**: ^2.0 (pour l'authentification JWT)
- **fruitcake/laravel-cors**: ^1.0 (pour la gestion des CORS)
- **Base de données**: SQLite (par défaut pour le développement local, configurable pour MySQL/PostgreSQL)

## Prérequis

Assurez-vous d'avoir les éléments suivants installés sur votre machine :

- PHP >= 8.1
- Composer
- Extensions PHP nécessaires (mbstring, xml, curl, pdo_sqlite ou pdo_mysql/pdo_pgsql)

## Installation

1.  **Cloner le dépôt :**
    ```bash
    git clone <URL_DU_DEPOT_BACKEND>
    cd payment_backend
    ```

2.  **Installer les dépendances Composer :**
    ```bash
    composer install
    ```

3.  **Générer la clé d'application :**
    ```bash
    php artisan key:generate
    ```

4.  **Générer la clé secrète JWT :**
    ```bash
    php artisan jwt:secret
    ```

## Configuration

1.  **Copier le fichier d'environnement :**
    ```bash
    cp .env.example .env
    ```

2.  **Modifier le fichier `.env` :**
    Ouvrez le fichier `.env` et configurez les paramètres de votre base de données. Par défaut, le projet est configuré pour utiliser SQLite. Si vous souhaitez utiliser MySQL ou PostgreSQL, mettez à jour les variables `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` en conséquence.

    Exemple pour SQLite (par défaut) :
    ```env
    DB_CONNECTION=sqlite
    # DB_DATABASE=./database/database.sqlite (sera créé automatiquement si inexistant)
    ```

    Exemple pour MySQL :
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=payment_db
    DB_USERNAME=root
    DB_PASSWORD=your_password
    ```

3.  **Configuration CORS :**
    Le middleware CORS est déjà configuré pour permettre les requêtes depuis n'importe quelle origine (`*`). Si vous souhaitez restreindre les origines, modifiez le fichier `config/cors.php`.

## Base de Données

1.  **Créer le fichier de base de données SQLite (si vous utilisez SQLite) :**
    ```bash
    touch database/database.sqlite
    ```

2.  **Exécuter les migrations pour créer les tables :**
    ```bash
    php artisan migrate
    ```

3.  **(Optionnel) Exécuter les seeders pour des données de test :**
    ```bash
    php artisan db:seed
    ```
    (Vous devrez créer les seeders `UserSeeder` et `PaymentSeeder` si vous souhaitez des données de test.)

## Exécution du Serveur

Pour démarrer le serveur de développement Laravel :

```bash
php artisan serve
```

Le serveur sera accessible à l'adresse `http://127.0.0.1:8000`.

## Endpoints API

La documentation complète des endpoints API est disponible dans le fichier `api_endpoints.md`.

## Tests

Pour exécuter les tests unitaires et fonctionnels :

```bash
php artisan test
```

## Déploiement

Ce backend peut être déployé sur n'importe quel service d'hébergement PHP/Laravel (ex: Render, Heroku, AWS EC2, DigitalOcean). Assurez-vous que votre environnement de production est configuré avec les bonnes variables d'environnement et une base de données robuste.

## Structure du Projet

La structure détaillée du projet est décrite dans le fichier `project_structure.md`.# payment-backend
