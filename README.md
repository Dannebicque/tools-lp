# Outils pour le développement Web (TSP1B103)

Ce cours sur les outils pour le développement web se décompose en deux partie. L'une sur les outils pour le développement back et l'autre sur les outils pour le développement front.

Cette partie du cours concerne les outils pour le développement back. Le cours portera essentiellement sur les outils de testing du code PHP produit.
Le cours consistera également en une série d'astuce et de bonnes pratique afin de produire un code de qualité, maintenable plus facilement.

Le cours se base sur un projet Symfony, mais les concepts (et les outils/librairies) peuvent être exploités sur n'importe quel projet Web en PHP.

## Plan et sommaire du cours

* [Concepts du testing](#concepts-du-testing)
  * [Tests unitaires](#tests-unitaires)
  * [Tests fonctionnels](#tests-fonctionnels)
* [Outils existants](#outils-existants)
  * [Rapide comparatif](#rapide-comparatif)
  * [Utilisation de PHPUnit](#utilisation-de-PHPUnit)
* [Cas Pratique](#cas-pratique)
  * [Récupération du projet de démonstration](#Recuperation-du-projet-de-demonstration)
  * [Application au projet de démonstration](#Application-au-projet de-demonstration)
  * [Exemple guidé](#Exemple-guide)
  * [Les autres tests à mettre en place](#Les-autres-tests-a-mettre-en-place)
* [Tester les liens](#Tester-les-liens)
  * [Théorie](#theorie)
  * [Cas pratique](#cas-pratique)
* [Tester les formulaires](#Tester-les-formulaires)
  * [Théorie](#theorie)
  * [Cas pratique](#cas-pratique)
* [Tester la base de données](#Tester-la-base-de-donnees)
  * [Théorie](#theorie)
  * [Cas pratique](#cas-pratique)
* [Tester avec un accès sécurisé](#Tester-avec-un-acces-sécurise)
  * [Théorie](#theorie)
  * [Cas pratique](#cas-pratique)
* [Tests Unitaires](#Tests-Unitaires)
  * [Théorie](#theorie)
  * [Cas pratique](#cas-pratique)

## Concepts du testing

En informatique, un test désigne une procédure de vérification partielle d'un système. Son objectif principal est d'identifier un nombre maximum de comportements problématiques du logiciel. Il permet ainsi, dès lors que les problèmes identifiés seront corrigés, d'en augmenter la qualité.

L'écriture de tests permet de s'assurer que son code fonctionne dans toutes les situations possibles, sans devoir passer par une phase (longue, fastidieuse et innéficace) de tests manuels.

Un test est du code permettant de tester du code.

# Tests unitaires

# Tests fonctionnels

## Outils existants

# Rapide comparatif

# Utilisation de PHPUnit


## Cas Pratique

# Récupération du projet de démonstration

Une application basique (DUTAF pour les MMI), est disponible sur le dépôt : [https://github.com/Dannebicque/dutafLP.git](https://github.com/Dannebicque/dutafLP.git)

Ce projet est composé de deux tables, d'une gestion des articles, des fournisseurs et d'une page d'accueil.

L'apparence n'a pas d'importance et fera l'objet d'un TP autour de Webpack-Encore.

Récupérer le projet, configurer la base de donner, créer la table et les tables, ajouter quelques données. On pourrait écrire des fixtures afin de peupler la base de données avec des données fivtives [Documentation sur les fixtures](https://symfony.com/doc/2.0/bundles/DoctrineFixturesBundle/index.html).

# Application au projet de démonstration

Installation des éléments pour les tests (dont PHPUnit)

````
composer require test-pack
````

Tous les fichiers de tests doivent êtres écrits dans le répertoire "tests/" de votre projet.

On peut utiliser la console et le maker pour créer la structure d'un fichier de test.

````
bin/console make:functional-test
````

Pour exécuter l'ensemble des tests avec la configuration par défaut il faudra executer la commande suivante :

````
bin/phpunit
````

Lors de la première exécution il est possible que PhpUnit se télécharge dans votre projet.

# Exemple guidé

Testons par exemple si notre page d'accueil fonctionne correctement. Pour cela, on peut effectuer un test qui simule l'accès à l'URL "/" et qui regarde si la réponse est 200 (page rendue correctement) et si le contenu de la page est celui attendu. Par exemple, on recherche le contenu de notre balise H1. Si le test trouve la phrase, alors il sera valide.

````
bin/console make:functional-test
````

La console vous demande un nom de fichier de test

````
 The name of the functional test class (e.g. DefaultControllerTest):
 > DefaultControllerTest

 created: tests/DefaultControllerTest.php
````

Le code produit est équivalent à celui ci-après

````
<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testSomething()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Liste des articles', $crawler->filter('h1')->text());
        // Liste des articles est le texte recherché dans la page, dans la balise h1.
    }
}
````

Dans l'exemple ci-dessous, on execute un test (une méthode) et deux assertions (deux vérifications dans la même méthode (200 et le contenu de la balise H1).

# Les autres tests à mettre en place

Ecrivez les différents tests afin de vérifier que toutes vos pages sont accessibles.

## Tester les liens

# Théorie

# Cas pratique

## Tester les formulaires

# Théorie

# Cas pratique

## Tester la base de données

# Théorie

# Cas pratique

## Tester avec un accès sécurisé

# Théorie

# Cas pratique

## Tests Unitaires

# Théorie

# Cas pratique
