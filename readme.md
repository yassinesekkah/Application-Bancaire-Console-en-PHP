🏦 Application Bancaire – PHP (Console)

📌 Description

Ce projet est une application bancaire en ligne de commande (console) développée en PHP orienté objet, utilisant PDO pour l’accès à la base de données.
L’objectif est de gérer des clients et leurs comptes bancaires (compte courant et compte épargne) en respectant les règles métier.

⚙️ Technologies utilisées

PHP (POO)

MySQL

PDO

Architecture Repository

Console (CLI)

📁 Structure du projet
Application-Bancaire-Console-en-PHP/
│
├── config/
│   └── database.php
│
├── src/
│   ├── Entity/
│   │   ├── Client.php
│   │   ├── Compte.php        (abstract)
│   │   ├── CompteCourant.php
│   │   └── CompteEpargne.php
│   │
│   └── Repository/
│       ├── ClientRepository.php
│       └── CompteRepository.php
│
├── console/
│   └── app.php
│
└── README.md

👤 Gestion des clients (US implémentées)
✔ Ajouter un client

Validation des données

Vérification de l’unicité de l’email

Insertion en base de données

✔ Lister tous les clients

Récupération depuis la DB

Conversion en objets Client

✔ Rechercher un client par ID

Retourne un objet Client ou null

✔ Modifier un client

Vérifie si le client existe

Validation du nom et de l’email

Email unique obligatoire

✔ Supprimer un client

Impossible de supprimer un client s’il possède un compte bancaire

💳 Gestion des comptes bancaires
✔ Classe abstraite Compte

Attributs communs (id, solde, clientId)

Méthodes abstraites :

deposit(float $amount)

withdraw(float $amount)

✔ CompteCourant

Frais de dépôt : 5 DH

Autorisation de découvert

Validation des montants

✔ CompteEpargne

Dépôt sans frais

Retrait limité au solde disponible

✔ Création d’un compte bancaire (US06)

Vérification que le client existe

Création via CompteRepository

Polymorphisme :

courant → CompteCourant

epargne → CompteEpargne

Un seul compte courant autorisé par client