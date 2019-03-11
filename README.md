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
  * [Application au projet de démonstration](#Application-au-projet-de-demonstration)
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

### Tests unitaires

Un test unitaire est un test sur une seule classe PHP, également appelée unité. Si vous souhaitez tester le comportement général de votre application, reportez-vous à la section relative aux tests fonctionnels.

Un test unitaire va par exemple tester une méthode spécifique, ou les méthodes d'une classe dédiée à des calculs ou des concepts métiers (les services dans symfony par exemple).

### Tests fonctionnels

Les tests fonctionnels vérifient l'intégration des différentes couches d'une application (du routage aux vues). En ce qui concerne PHPUnit, ils ne diffèrent pas des tests unitaires, mais ils ont un flux de travail très spécifique:

1. Faire une demande;
2. Cliquez sur un lien ou soumettez un formulaire.
3. Tester la réponse;
4. "Rincer" et répéter.

Les tests unitaires nécessitent d'utiliser des bundles permettant de naviguer dans le code produit (des crawler), mais également permettant de "simuler" un navigateur (browser) afin de récuperer les réponses. Par défaut ces outils sont dans le *pack-test* de symfony.

## Outils existants

### Rapide comparatif

Il existe globalment deux outils utilisés par la communauté PHP :  PHPUnit et Behat. Ces deux outils peuvent être complémentaire (ils n'ont pas exactement la même approche du testing), ou utilisés de manière indépendante (ils peuvent malgré tout effectuer les mêmes tests, ou en tout cas attester du bon fonctionnement de votre application.

Cependant, on va préférer choisir Behat pour tester uniquement un comportement d'application (BDD : Behaviour Driven Design), sans se soucier du fonctionnement interne de l'application (ce que pourrait malgré tout faire Behat). PHPunit est plutôt orienté sur le fonctionnemet même de l'application (tests unitaires par exemple), mais peut aussi executer des tests fonctionnels. On qualifie généralement PHPUnit d'outil de TDD (Test Data Driven).

Il peut parfois être pertinent d'utiliser les deux outils afin de profiter de leurs points forts respectifs.

### Utilisation de PHPUnit

Symfony s’intègre à une bibliothèque indépendante appelée [PHPUnit](https://phpunit.de/) pour vous fournir un cadre de test complet. Vous pouvez consulter la [documentation très complète de PHPUnit](https://phpunit.readthedocs.io/en/8.0/), dont il existe une version française plutôt bien maintenue à jour.

## Cas Pratique

### Récupération du projet de démonstration

Une application basique (DUTAF pour les MMI), est disponible sur le dépôt : [https://github.com/Dannebicque/dutafLP.git](https://github.com/Dannebicque/dutafLP.git)

Ce projet est composé de deux tables, d'une gestion des articles, des fournisseurs et d'une page d'accueil.

L'apparence n'a pas d'importance et fera l'objet d'un TP autour de Webpack-Encore.

Récupérer le projet, configurer la base de donner, créer la table et les tables, ajouter quelques données. On pourrait écrire des fixtures afin de peupler la base de données avec des données fivtives [Documentation sur les fixtures](https://symfony.com/doc/2.0/bundles/DoctrineFixturesBundle/index.html).

Téléchargement du projet :

En vous placant dans votre répertoire www ou public_html :

````
git clone https://github.com/Dannebicque/dutafLP.git

cd dutafLP/
````

Mettre à jour le fichier .env, en le dupliquant .env.local et en adaptant vos informations.

Installer les vendors :

````
composer install
````

Installer la base de données

````
bin/console doctrine:database:create
bin/console make:migrations
bin/console doctrine:make:migration
````

Tester le site :

[http://localhost/dutafLP/public/index.php/](http://localhost/dutafLP/public/index.php/)

Adapter l'URL selon votre configuration.

Vous pouvez avoir les articles ou les fournisseurs avec les URL suivantes :

[http://localhost/dutafLP/public/index.php/article/](http://localhost/dutafLP/public/index.php/article/) et 
[http://localhost/dutafLP/public/index.php/fournisseur/](http://localhost/dutafLP/public/index.php/fournisseur/)


### Application au projet de démonstration

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

### Exemple guidé

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

Dans l'exemple ci-dessous, on execute un test (une méthode) et deux assertions (deux vérifications dans la même méthode (la réponse est égale à 200 et le contenu de la balise H1).

La méthode request() retourne un **crawler** (*robot*), qui est un object contenant, entre autre, la réponse du navigateur. 

Pour executer le test, il faut lancer PHPUnit :

````
bin/phpunit
````

Si tout se passe bien, vous devriez avoir 1 test, avec 2 assertions.

### Les autres tests à mettre en place

Ecrivez les différents tests afin de vérifier que toutes vos pages sont accessibles. **On ne testera que les URLs dans ce premier exercice.**

## Tester les liens

### Théorie

Le robot peut également être utilisé pour interagir avec la page (détecter un élément (une balise), des liens ou encore un formulaire). Cliquez sur un lien en le sélectionnant d'abord avec le robot à l'aide d'une expression [XPath](https://www.w3schools.com/xml/xpath_intro.asp) ou d'un sélecteur CSS, puis utilisez le client pour cliquer dessus.

Pour tester les liens contenus dans une page, il faut :

1. Executer une requète pour accéder à la page
2. Obtenir le crawler/robot associé afin de la parcourir
3. Parcourir avec le crawler à la recherche de votre élément
4. Simuler le click
5. Regarder la réponse obtenue et la comparer si besoin

### Cas pratique

````
 public function testClickAcheter() {

     $client = static::createClient();
     $crawler = $client->request('GET', '/');

     $link = $crawler
         ->filter('a:contains("Acheter")') // find all links with the text "Greet"
         ->eq(0) // select the second link in the list
         ->link()
     ;

     $crawler = $client->click($link);
     $this->assertSame(200, $client->getResponse()->getStatusCode());

 }
 ````

## Tester les formulaires

### Théorie

Pour tester un formulaire, et tout particulierement sa soumission, la démarche est assez similaire.

1. Executer une requète pour accéder à la page
2. Obtenir le crawler/robot associé afin de la parcourir
3. Parcourir avec le crawler à la recherche d'un bouton de type submit
4. Eventuellement adapter les valeurs du formulaire
4. Simuler le click
5. Regarder la réponse obtenue et la comparer si besoin

### Cas pratique

````
 public function testValideFormulaire() {

     $client = static::createClient();
     $crawler = $client->request('GET', '/');

    $form = $crawler->selectButton('submit')->form();

    // set some values
    $form['name'] = 'Lucas';
    $form['form_name[subject]'] = 'Hey there!';

    // submit the form
    $crawler = $client->submit($form);
    $this->assertSame(200, $client->getResponse()->getStatusCode());

 }
 ````
### Application

Ecrire un test qui vérifie votre formulaire de création d'un fournisseur. Selon-vous, et selon le code écrit quel est la réponse attendue ?
Que pouvez vous noter sur votre base de données ?

## Tester le contenu de vos pages

Grâce au Crawler vous pouvez donc vous assurer du contenu de vos pages. En effet, idéalement, il ne faudrait pas simplement s'assurer de la réponse du navigateur (même si vérifier déjà cela permet de s'assurer de ne pas avoir de liens morts). Mais rien ne garanti que la page affichée correspond à celle qui devrait l'être.

Il existe de très nombreuses "assert" pour tester de nombreux cas de figure. Symfony propose la liste suivante comme tests de base :

````
// asserts that there is at least one h2 tag
// with the class "subtitle"
$this->assertGreaterThan(
    0,
    $crawler->filter('h2.subtitle')->count()
);

// asserts that there are exactly 4 h2 tags on the page
$this->assertCount(4, $crawler->filter('h2'));

// asserts that the "Content-Type" header is "application/json"
$this->assertTrue(
    $client->getResponse()->headers->contains(
        'Content-Type',
        'application/json'
    ),
    'the "Content-Type" header is "application/json"' // optional message shown on failure
);

// asserts that the response content contains a string
$this->assertContains('foo', $client->getResponse()->getContent());
// ...or matches a regex
$this->assertRegExp('/foo(bar)?/', $client->getResponse()->getContent());

// asserts that the response status code is 2xx
$this->assertTrue($client->getResponse()->isSuccessful(), 'response status is 2xx');
// asserts that the response status code is 404
$this->assertTrue($client->getResponse()->isNotFound());
// asserts a specific 200 status code
$this->assertEquals(
    200, // or Symfony\Component\HttpFoundation\Response::HTTP_OK
    $client->getResponse()->getStatusCode()
);

// asserts that the response is a redirect to /demo/contact
$this->assertTrue(
    $client->getResponse()->isRedirect('/demo/contact')
    // if the redirection URL was generated as an absolute URL
    // $client->getResponse()->isRedirect('http://localhost/demo/contact')
);
// ...or simply check that the response is a redirect to any URL
$this->assertTrue($client->getResponse()->isRedirect());
````

## Tester de nombreuses données.

### Théorie

Dans notre cas il est simple et rapide d'écrire les tests. Le nombre de page est réduit, et il est d'ailleur presque superflu d'utiliser des tests pour une application aussi légére.

Lorsque l'application commence à grossier et que les URLs se multiplient, il devient possible de définir un ensemble de données (un tableau d'URL à tester par exemple), et de les tester successivement. PhPUnit nous informera des URLs posant problème.

### Cas pratique

````
/**
 * @dataProvider provideUrls
 */
public function testPageIsSuccessful($url)
{
    $client = self::createClient();
    $client->request('GET', $url);

    $this->assertTrue($client->getResponse()->isSuccessful());
}

public function provideUrls()
{
    return [
        ['/'],
        ['/blog'],
        ['/contact'],
        // ...
    ];
}
````

Ne pas oublier l'annotation sur la méthode de test c'est elle qui permet de faire le lien avec le tableau d'URL à tester. Chaque URL va correspondre à un test.

## Simuler d'autres requêtes

Dans les exemples précédents nous n'avons testés que des requêtes de type Get (ou POST lors de l'envoi du formulaire. l est psosible de tester des requetes GET, POST, DELETE, PUT, des appels AJAX, de l'Upload de fichier, ...

Tout ce que vous pouvez faire sur votre application peut être testé via PHPUnit.

### Point Behat

Dans ce contexte de navigation "complexe" Behat pourrait sembler plus adapté, car Behat propose la possibilité d'écrire un scénario (avec une syntaxe propre), sans devoir écrire tout le code PHP qui va venir simuler chaque interaction d'un utilisateur. 

Exemple de scénario:

````
Feature: Product basket
  In order to buy products
  As a customer
  I need to be able to put interesting products into a basket

  Rules:
  - VAT is 20%
  - Delivery for basket under £10 is £3
  - Delivery for basket over £10 is £2

  Scenario: Buying a single product under £10
    Given there is a "Sith Lord Lightsaber", which costs £5
    When I add the "Sith Lord Lightsaber" to the basket
    Then I should have 1 product in the basket
    And the overall basket price should be £9

  Scenario: Buying a single product over £10
    Given there is a "Sith Lord Lightsaber", which costs £15
    When I add the "Sith Lord Lightsaber" to the basket
    Then I should have 1 product in the basket
    And the overall basket price should be £20

  Scenario: Buying two products over £10
    Given there is a "Sith Lord Lightsaber", which costs £10
    And there is a "Jedi Lightsaber", which costs £5
    When I add the "Sith Lord Lightsaber" to the basket
    And I add the "Jedi Lightsaber" to the basket
    Then I should have 2 products in the basket
    And the overall basket price should be £20
````

## Parcourir

Le client prend en charge de nombreuses opérations pouvant être effectuées dans un navigateur réel:

````
$client->back();
$client->forward();
$client->reload();

// clears all cookies and the history
$client->restart();
````

REMARQUE
Les méthodes back()et forward()ignorent les redirections qui peuvent s'être produites lors de la demande d'une URL, comme le font les navigateurs classiques.

## Tester la base de données

### Théorie
Lors d'un test fonctionnel, vous devrez peut-être préparer une base de données de test avec des valeurs prédéfinies pour vous assurer que votre test dispose toujours des mêmes données.
En effet, vous l'avez remarqué en testant le formulaire, les données sont ajoutées dans la base de données "principale" de votre application, ce qui peut poser des problèmes.

### Cas pratique

Il est possible de redéfinir la variable de connexion des fichiers .env (DATABASE_URL) dans le fichier de configuration PHPUnit : phpunit.xml

Dans ce fichier vous pouvez configurer les tests à effectuer, le paramétrage de votre aplication, et donc l'accès à une base de données de tests. Si PHPUnit trouve une ligne de connexion dans ce fichier il ignaurera les informations de votre fichier .env (ou .env.local)

Il vous faudra aussi définir un fichier .env.test (à partir de la version 4.2), qui contiendra cette configuration de test. Cela permettra de disposer de deux bases de données. Une pour le mode de développement (ou production) et une pour les tests.

[https://symfony.com/doc/current/testing/database.html](https://symfony.com/doc/current/testing/database.html)

#### Le fichier phpunit.xml

Ajouter la ligne ci-dessous avec vos informations:
````
<php>
   ...
   <env name="DATABASE_URL" value="mysql://USER:PASSWORD@127.0.0.1:8889/basedetest" />
</php>
````

#### Le fichier .env.test

````
DATABASE_URL=mysql://USER:PASSWORD@127.0.0.1:8889/basedetest
````

Il faut ensuite créer la base de données, mettre à jour les tables, et éventuellement executer des fixtures pour l'alimenter.
Il faut bien penser à préciser l'environnement `--env=test`.

````
bin/console doctrine:database:create --env=test
bin/console doctrine:schema:update -f --env=test //ou via les migrations
````

Vous pouvez vous faire un script qui avant chaque phase de test :

1. efface la base de données de test,
2. Créé la base de données de test
3. Ajoute les tables
4. Execute éventuellement les fixtures
5. Execute PHPUnit

## Tests Unitaires

### Théorie

### Cas pratique
