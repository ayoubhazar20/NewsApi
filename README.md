# Api News

## Table des matières

- [Description du Projet](#description-du-projet)
- [Fonctionnalités](#fonctionnalités)
- [Installation](#installation)
- [Utilisation](#utilisation)
- [Contributions](#contributions)
- [Licence](#licence)

## Description du Projet

Ce projet vise à créer une API REST pour gérer des actualités, dotée de fonctionnalités d'ajout, de suppression et de modification des actualités. L'objectif principal est d'afficher la liste des actualités dans l'ordre décroissant de leur publication, en excluant les actualités expirées.

## Fonctionnalités

- Ajout, suppression et modification des actualités.
- Affichage de la liste des actualités dans l'ordre décroissant de leur publication.
- Création d'un modèle de données pour les actualités avec les champs Titre, Contenu, Catégorie, Date de Début et Date d'Expiration.
- Mise en place d'un modèle de données pour une structure arborescente de catégories d'actualités.
- Développement d'un contrôleur pour gérer les opérations CRUD sur les actualités.
- Création d'un middleware pour restreindre l'accès à l'API aux utilisateurs authentifiés.
- Définition des routes API pour les opérations CRUD sur les actualités.
- Affichage des actualités dans l'ordre décroissant de leur date de publication.
- Mise en œuvre d'un algorithme de recherche récursif pour trouver la catégorie demandée et récupérer tous les articles associés.
- Développement d'une nouvelle route dans l'API pour rechercher une catégorie spécifique dans l'arborescence et renvoyer tous les articles associés.
- Utilisation d'outils comme Postman pour tester les fonctionnalités de l'API.
- Assurer que l'API répond aux codes d'état HTTP appropriés pour chaque opération.

## Installation

Suivez ces étapes pour configurer le projet sur votre machine locale :

1. Clonez le dépôt :
   ```bash
   git clone https://github.com/ayoubhazar20/NewsApi.git
   ```

2. Accédez au répertoire du projet :
   ```bash
   cd NewsApi
   ```

3. Installez les dépendances PHP :
   ```bash
   composer install
   ```

4. Copiez `.env.example` en `.env` et configurez vos variables d'environnement.

5. Générez la clé de l'application :
   ```bash
   php artisan key:generate
   ```

6. Migrez la base de données :
   ```bash
   php artisan migrate
   ```

7. Seeder la base de données avec des catégories (optionnel) et User :
   ```bash
   php artisan db:seed --class=CategorySeeder
   ```
   ```bash
   php artisan db:seed --class=UsersTableSeeder
   ```
8. Pour s'authentifier utiliser :
   ```bash
   email : user@gmail.com
   password : password 
    ```

9. Installez les dépendances JavaScript :
   ```bash
   npm install
   ```
