# Projet Symfony pour le site atelier-amelie.fr

[![Build Status](https://drone.reges.fr/api/badges/alexandre-dos-reis/atelier-d-amelie-symfony-docker/status.svg)](https://drone.reges.fr/alexandre-dos-reis/atelier-d-amelie-symfony-docker)

Ceci est le projet symfony pour [atelier-amelie.fr](https://atelier-amelie.fr) :

## TODOs

- Commun
  ~~- Implémenter la page login.~~
  - Implémenter des animations
    - [CSS Page Transitions](https://symfonycasts.com/screencast/turbo/transitions)

- Boutique
  - Changer les cartes produits pour la boutique [de ce type](https://www.reeftleathergoods.com/la-boutique)
  - Faire une page produit unique en ajax avec filtres.
  - Rédiger les Conditions Générales de Vente.
  - Les clicks sur ajouter au panier échoue avec une url du type : `GET https://localhost:8000/uploads/products/undefined 404`

- Commande
  - Permettre de choisir entre une livraison(paiement des frais de port) ou un retrait à domicile (pas de frais de port).
  - Dans la page suivi de commande : Permettre le retour d'une commande via un formulaire de retour.
  - Créer une CLI Symfony pour supprimer les commandes "En attente de paiement" créées il y a plus d'une semaine (par exemple), et lancer cette commande via Cron.

- Galerie
  - Implémenter une page unique avec React
  - Vider le cache des liens de galerie à chaque Ajout/Edition/Suppression de catégories.

- Entities
  - Transformer les constantes en ENUM ou se débrouiller pour enregistrer des valeurs numériques en BDD. 
  
- Admin
  - Générer des factures en PDF sur chaque commande. https://packagist.org/packages/knplabs/knp-snappy
  - Envoi de mail (Docker - Mail catcher)
  ~~- Implémenter des stats visibles en admin avec Chart JS - Presque fini !~~
  - Implémenter une tâche en arrière plan pour résoudre des infos sur les adresses IP stockées en BDD.
    - [Créer une commande symfony via la console](https://symfony.com/doc/current/components/console.html)
    - Installer cette librairie [ip2location](https://packagist.org/packages/ip2location/ip2location-php).
    - La démarrer avec cron job.

- Editeur d'image
  ~~- Gérer la suppression des images pour ImageProduct dans le listener VichUploader.~~
  ~~- Voir pourquoi les images watermarked ne sont pas chargées dans la boutique…~~
  - Créer un service pour mutualiser le listener VichUploader et le controlleur ImageEditor. 

- Docker
  - Passer l'image Dockerfile en Alpine pour réduie la taille de l'image

## Mise en place

### Installation

```bash
composer install
npm install --force
```

### Démarrer l'environnement de développement

```bash
docker-compose -f docker/dev/docker-compose.yaml up -d
symfony serve -d
npm run dev-server
```

### Maj de la DB et migrations

```bash
php bin/console make:migration
php bin/console doctrine:migrations:migrate --no-interaction
```

### Ajouter des données de test

```bash
php bin/console doctrine:fixtures:load -n
```

### Vider le cache

```bash
php bin/console cache:clear
```

## Commandes perso

### Créer des administrateurs

Lancer la commande suivante pour créer un administrateur
```bash
symfony console app:create-admin username@mail.com password
```

## Prod

Create 2 .env files
- `.env.local` based on 

```bash
composer require symfony/runtime
php bin/console d:m:m --no-interaction
docker-compose -f docker-compose-prod.yaml --env-file ./.env.prod up -d --build --force-recreate
```

## Stripe

### Tester la confirmation de paiement Stripe en développement

- Créer une URL publique avec Ngrok `ngrok http https://127.0.0.1:8000/` pointant sur l'URL du serveur de développement.
- Copier l' URL généréé par Ngrok.
- Se rendre dans [webhook Stripe](https://dashboard.stripe.com/test/webhooks).
- Ajouter un endpoint.
- Coller l'URL générée par Ngrok suivi du chemin acceptant des requêtes JSON en POST comme ceci : `http://url.ngrok.io/paiement/confirmation`
- Ecouter sur l'évènement appelé `payment_intent.succeeded`.
- Copier la clé secrète de signature, la coller dans le fichier `.env.local` sur la clé : `STRIPE_CONFIRM_END_POINT_KEY`.
- Effectuer un achat sur le site
- Vérifier que Stripe récupère bien une requête au statut 200.

## Drone

### Chiffrer des secrets directement dans le fichier `.drone`

- Avoir la CLI drone installée, sinon faire `brew install drone-cli`
- Configurer les variables d'environnement suivantes :
  - DRONE_SERVER
  - DRONE_TOKEN
- Commande de chiffrement: `drone encrypt <repository> <secret>`

Exemple :

```bash
drone encrypt alexandre-dos-reis/atelier-d-amelie-symfony-docker username
AIJtbdfcS+HdONICoc1e2Z5EbAxV0Uy8dBf63aBA1FXP0quc
```

## Docker

- [VIDEO : DOCKERISER une application SYMFONY](https://www.youtube.com/watch?v=KFWnB5hW6j8&list=PLxEJ5uJLOPDykEApcRHzprbFNWHHdYKAM&index=24) 
  - Dockerfile 8:50.
  - docker-compose 31:00

- [Easy installation of PHP extensions in official PHP Docker images](https://github.com/mlocati/docker-php-extension-installer)