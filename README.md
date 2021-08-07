# Développez de A à Z le site communautaire SnowTricks
[![SymfonyInsight](https://insight.symfony.com/projects/31bb2792-8590-42a0-9fd0-82a045a6219e/mini.svg)](https://insight.symfony.com/projects/31bb2792-8590-42a0-9fd0-82a045a6219e)

Projet réalisé dans le cadre du parcours développeur d'applications PHP / SYMFONY effectué chez [Openclassrooms](https://openclassrooms.com/).

## Installation du projet

En fonction de votre système d'exploitation plusieurs serveurs peuvent être installés :

    - Windows : WAMP (http://www.wampserver.com/)
    - MAC : MAMP (https://www.mamp.info/en/mamp/)
    - Linux : LAMP (https://doc.ubuntu-fr.org/lamp)
    - XAMP (https://www.apachefriends.org/fr/index.html)

## Clonage du projet

Installation de GIT :

    - GIT (https://git-scm.com/downloads) 

Télécharger Symfony CLI

    - https://symfony.com/download

Une fois GIT installé, il faudra vous placer dans le répertoire de votre choix puis exécuté la commande suivante :

    - git clone https://github.com/AlexandreCavanna/OC-Projet-6_SnowTricks

Le projet sera automatiquement copié dans le répertoire ciblé.

## Configuration des variables d'environnement

Configurez les variables d'environnement comme la connexion à la base de données dans le fichier env.local qui sera créé à la racine du projet en copiant le fichier .env.
Vous pourrez ensuite renseigner les identifiants de votre base de données en suivant le modèle ci-dessous.

    - DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7

## Création de la base de données

Créez la base de données de l'application en tapant la commande ci-dessous :

    - symfony console doctrine:database:create

Puis lancer la migration pour créer les tables dans la base de données :

    - symfony console doctrine:migrations:migrate    

## Gestion des assets

Vous pouvez générer à l'aide de Webpack vos assets Javascript et CSS avec NPM en tapant la commande ci-dessous :

    - yarn run dev (en dev)
    - yarn run build (en prod)

## Lancement du serveur

Vous pouvez lancer le serveur via la commande suivante :

    - symfony server:start

## Générer des fausses données

Vous pouvez générer des fausses données grâce la fixture présente dans le projet avec la commande suivante :

    - symfony console doctrine:fixtures:load

## Contexte du projet

Jimmy Sweat est un entrepreneur ambitieux passionné de snowboard. Son objectif est la création d'un site collaboratif pour faire connaitre ce sport auprès du grand public et aider à l'apprentissage des figures (tricks).

Il souhaite capitaliser sur du contenu apporté par les internautes afin de développer un contenu riche et suscitant l’intérêt des utilisateurs du site. Par la suite, Jimmy souhaite développer un business de mise en relation avec les marques de snowboard grâce au trafic que le contenu aura généré.

Pour ce projet, nous allons nous concentrer sur la création technique du site pour Jimmy.

## Description du besoin
Vous êtes chargé de développer le site répondant aux besoins de Jimmy. Vous devez ainsi implémenter les fonctionnalités suivantes :

    - un annuaire des figures de snowboard. Vous pouvez vous inspirer de la liste des figures sur Wikipédia. Contentez-vous d'intégrer 10 figures, le reste sera saisi par les internautes ;
    - la gestion des figures (création, modification, consultation) ;
    - un espace de discussion commun à toutes les figures.

Pour implémenter ces fonctionnalités, vous devez créer les pages suivantes :

    - la page d’accueil où figurera la liste des figures ; 
    - la page de création d'une nouvelle figure ;
    - la page de modification d'une figure ;
    - la page de présentation d’une figure (contenant l’espace de discussion commun autour d’une figure).

## Nota Bene

Il faut que les URLs de page permettent une compréhension rapide de ce que la page représente et que le référencement naturel soit facilité.

L’utilisation de bundles tiers est interdite sauf pour les données initiales, vous utiliserez les compétences acquises jusqu’ici ainsi que la documentation officielle afin de remplir les objectifs donnés.

Le design du site web est laissé complètement libre, attention cependant à respecter les wireframes fournis pour le gabarit de vos pages. Néanmoins il faudra que le site soit consultable aussi bien sur un ordinateur que sur mobile (téléphone mobile, tablette, phablette…).

En premier lieu il vous faudra écrire l’ensemble des issues/tickets afin de découper votre travail méthodiquement et vous assurer que l’ensemble du besoin client soit bien compris avec votre mentor. Les tickets/issues seront écrits dans un repository Github que vous aurez créé au préalable.

L’ensemble des figures de snowboard doivent être présentes à l’initialisation de l’application web. Vous utiliserez un bundle externe pour charger ces données.

## Compétences évaluées

    - Prendre en main le framework Symfony
    - Développer une application proposant les fonctionnalités attendues par le client
    - Gérer une base de données MySQL ou NoSQL avec Doctrine
    - Organiser son code pour garantir la lisibilité et la maintenabilité
    - Prendre en main le moteur de templating Twig
    - Respecter les bonnes pratiques de développement en vigueur
    - Sélectionner les langages de programmation adaptés pour le développement de l’application
