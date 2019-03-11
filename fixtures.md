# Professionnaliser les tests

Les exemples de la séance précédente peuvent présenter des défauts. En effet, lors de l'affiche de listes, rien ne nous garanti que le contenu de la base de données contient des données. De la même manière, supprimer ou éditer un élément se fait en supposant qu'il existe dans la base de données. Si ce n'est pas le cas le test ne fonctionnera pas et lévera une erreur. Une erreur qui n'est pas du à votre code, mais à l'absence de données.

Pour éviter ce type de problème il est recommandé d'utiliser des fixtures, qui sont executés à chaque début de tests. De cette manière, on s'assure qu'il y a des données présentent dans la base de données, et qu'elles correspondent à celles attendus dans les tests.

Nous allons donc voir dans cet partie du cours :

1. Comment écrire des fixtures
2. Comment écrire un script pour automatiser les tests
3. Comment mettre en place une sécurité dans Symfony
4. Comment tester la partie sécurisée avec PHPUnit.
5. Les concepts de l'injection de dépendance.
6. Les tests unitaires pour valider nos classes et leurs méthodes.

## Comment écrire des fixtures

### Principes

Les fixtures sont du code PHP qui permet d'insérer des données dans la base de données sans devoir utiliser PHPMyAdmin, des requètes SQL ou des formulaires. On utilise des fixtures pour alimenter son site lors des phases de développement (pour éviter de les saisir manuellement, et parce que l'on peut facilement générer une grande masse de données), pour effectuer des tests, ou éventuellement pour "initialiser" un site lors de sa mise en production (même si on preférera un script d'installation dans ce contexte).

L'execution des fixtures à pour effet d'effacer le contenu de votre base de données.

Pour utiliser les fixtures, il faut installer le bundle doctrine-fixture.

````
composer require orm-fixtures --dev   
````

Ensuite, il est possible d'utiliser la console et le "maker" pour construire la structure des fichiers de fixtures.

````
bin/console make:fixtures
````

Cela vous permet d'obtenir un fichier comme ci-dessous :

````
<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
````

Pour executer les fixtures il faudra écrire la ligne suivante :

````
bin/console doctrine:fixtures:load 
````

Toutes les fixtures (classes et méthodes) seront executées.

### Exemple concret

La fixture ci-dessous permet de créer un fournisseur.

````
<?php

namespace App\DataFixtures;

use App\Entity\Fournisseur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $fournisseur = new Fournisseur();
        $fournisseur->setTelephone('0123456789');
        $fournisseur->setNom('Fournisseur 1');
        $fournisseur->setVille('Troyes');

        $manager->persist($fournisseur);

        $manager->flush();
    }
}
````

### Application

 * Ecrire des fixtures pour créer 3 fournisseurs (on supprimer le second avec nos tests)
 * Ecire des fixtures pour créer 2 articles pour le fournisseur 1.
 
## Comment écrire un script pour automatiser les tests

Il est possible d'écrire un cours script "bash" ou "shell" par exemple afin d'automatiser notre processus de tests.

Le processus pourrait par exemple être le suivant :

1. Mettre à jour la base de données de tests,
2. Executer les fixtures,
3. Executer les tests.

Exemple d'un fichier test.sh

````
echo "Mise à jour de la base de données"
bin/console doctrine:schema:update -f --env=test
echo "Chargement des fixtures"
bin/console doctrine:fixtures:load --env=test --purge-with-truncate
echo "Execution des tests"
bin/phpunit
````

Libre à vous de le nommer, de l'adapter à vos besoins.

L'execution se fera avec la commande

````
./test.sh
````

## Comment mettre en place une sécurité dans Symfony

### Rappels sur la partie sécurité de Symfony

La sécurité est expliqué dans la partie du cours sur Symfony [Sécurité avec Symfony](https://github.com/Dannebicque/symfony-lp/blob/master/securite.md)

### Mise en place sur notre exemple

1. Mettre en place la sécurité sur notre projet. Sécuriser les "CRUD" pour articles et fournisseurs.
2. Executez vos tests... Que se passe-t-il ?
3. Créer une fixture afin d'avoir des utilisateurs pour nos tests.

## Comment tester la partie sécurisée avec PHPUnit.

Il n'est maintenant plus possible de tester la partie article ou fournisseur, car elles impliquent un accès sécurisé.

Il va donc falloir émuler un utilisateur existant (d'où l'intéret des fixtures) et naviger avec lui. De cette manière, il sera authentifié et authorisé et pouura accéder aux pages.

Il existe globalement deux approches :

* Avoir une configuration avec une authentification HTTP (le login et le password sont dans ce cas dans le fichier security.yaml). Cette configuration ne servant que pour la phase de tests.
* Simuler une authentification en créant le token qui simule une authentification réussie.

La première solution est rapide et permet de faire des tests simples, qui n'impliquent pas de récupérer des informations sur les usagers. La seconde solution est également simple, mis implique de générer le token dans chaque classe de tests. Cette méthode permet de récupérer les informations des utilisateurs. Couplée avec les fixtures elle est relativement simple à mettre en place, et peu gourmande en ressource.

*Si vous utilisez une solution d'authentification via OAuth, il serait préférable d'avoir une solution alternative pour les tests. En effet les tests impliquant une vérification avec OAuth sont très gourmand en ressources et vont ralentir considérablement la phase de tests.*

### Simulation du token

Il va falloir modifier notre classe de test pour que celle ci générer un token, sauvegarde les éléments en session et dans un cookie afin de pouvoir exploiter la sécurité de symfony et récupérer un User connecté.

Pour cela, on peut procéder de la manière suivante :

````
private $client = null;

public function setUp()
{
    $this->client = static::createClient();
}
````

Ecrire la méthode setUp(), qui est executée avant vos tests. Dans cette méthode on instancie le client.

Ensuite, si toutes les méthodes de cette classe impliquent une authentification, il est possible d'écrire le code suivant à la suite, dasn la méthode setUp(). Sinon, on peut créer une méthode private qui va être appelée uniquement sur les tests nécessitant une authentificaiton.

````
 $session = $this->client->getContainer()->get('session');

$firewallName = 'main'; //nom de votre firewall (voir security.yaml)
$firewallContext = 'main';

//création du toekn
$token = new UsernamePasswordToken('test@mail.com', 'test', 'main', ['ROLE_ADMIN']);
$session->set('_security_main', serialize($token));
$session->save();

$cookie = new Cookie($session->getName(), $session->getId());
$this->client->getCookieJar()->set($cookie);
````

L'objet UsernamePasswordToken prend 4 paramètres :
* Le login (username ou mail selon votre configuration)
* le password
* le nom du firewall
* le rôle de l'utilisateur

A ce stade l'utilisateur n'est pas obligé d'exister dans votre base de données, et les données peuvent donc être totalement fictives.

### Mise en place sur notre exemple

Modifier vos tests afin de pouvoir accéder aux URL des parties sécurisées.

## Les concepts de l'injection de dépendance (*dependency injection* en anglais).

### Concepts de l'Injection de dépendances

``
Il consiste à créer dynamiquement (injecter) les dépendances entre les différents objets en s'appuyant sur une description (fichier de configuration ou métadonnées) ou de manière programmatique. Ainsi les dépendances entre composants logiciels ne sont plus exprimées dans le code de manière statique mais déterminées dynamiquement à l'exécution.
``

Ce concept existe depuis toujours dans Symfony, mais était complexe à mettre en oeuvre dans SF2 et SF3 (<3.3). Comme l'indique la définition, il fallait passer par un fichier de configuration pour l'exploiter et définir les liens existants.

Aujourd'hui avec SF 4 (et >3.4), ce concept est quasiment transparent dans son utilisation, et semble naturel lorsque l'on le découvre avec SF4. Vous avez déjà écrit de l'injection de dépendance depuis le début du module en LP.

Par exemple :

````
public function index(ArticleRepository $articleRepository)
````

Est une méthode qui utilise l'injection de dépendance. Article Repository est injecté dynamiquement dans la méthode. Vous ne passez pas cet élement dans votre appel de la méthode. Ce concept est très pratique et permet d'alléger le code (mais augmente le nombre de paramètres de vos méthodes).

Ce comportement fonctionne parce que dans Symfony( SF>3.3), toutes les classes contenues dans src/ peuvent être injectées. C'est ce que décrit le fichier services.yaml

````
services:
    # default configuration for services in *this* file
    _defaults:
        autowire: true      # Automatically injects dependencies in your services.
        autoconfigure: true # Automatically registers your services as commands, event subscribers, etc.

    # makes classes in src/ available to be used as services
    # this creates a service per class whose id is the fully-qualified class name
    App\:
        resource: '../src/*'
        exclude: '../src/{DependencyInjection,Entity,Migrations,Tests,Kernel.php}'
````

Grâce à l'autowiring, il n'est plus nécessaire de décrire le comportement de l'injection de dépendance, et il est défini de manière automatique (et systèmatique) pour toutes les classes de src.

*Notez que Entity est exclus, pour autant on peut utiliser Article dans les paramètres d'une méthode. Dans ce cas précis, on utiliser la notion de ParamConverters et pas d'injection de dépendance*

De ce fait, il est possible d'écrire nos propres classes et de les utiliser en les injectants dans les méthodes qui en ont besoin.

### Ecrire une classe de traitement

Ecrivons par exemple une classe qui va calculer un tarif TTC à partir d'un prix HT.

On pourrait définir une classe dans un répertoire nommé Classes dans le répertoire src. Par défaut, puisqu'il se trouve dans src, il sera automatiquement injectable dans n'importe qu'elle méthode.

Cette classe contiendra par exemple une méthode pour calculer le montant TTC à partir d'un prix HT.
Et on peut imaginer utiliser cette méthode dans la méthode "show" de article, afin d'enviyer à la vue le prix TTC du produit.

**Oui ! Ca ne sert à rien ! Mais pour l'exercice... On pourrait effectuer ce clacul au niveau de l'entité en ajoutant une méthode.**

Pour résumer on aurait :

````
namespace App\Classes;


class Calculs
{
    const TVA = 19.6;

    public function calculTTC($prixHT) {
        return $prixHT + ($prixHT * self::TVA / 100);
    }
}
````

Et pour l'injection de dépendance dans la méthode show :

````
public function show(Calculs $calculs, Article $article): Response
````


### Pour s'amuser

Ajotuer une méthode dans la classe calcul qui calcul le prix TTC du stock pour un article donné. (on passera en paramètre la quantité et le prix de l'article)

**Oui ! La uassi on pourrait le faire directement dans l'entité de manière plus judicieuse.**

## Les tests unitaires pour valider nos classes et leurs méthodes.

### Concepts

Un test unitaire permet de tester individuellement les méthodes d'une classe, afin de s'assurer de la cohérence du résultat. Les tests sont effectués de manière déconnecté du contexte (pas dans un contexte de navigation, sns connexion, dans données issues de la BDD, ...)

L'exemple ci-dessous est un test permettant de vérifier la méthode calculTTC décrite précédemment.

````
<?php

namespace App\Tests;

use App\Classes\Calculs;
use PHPUnit\Framework\TestCase;

class CalculTest extends TestCase
{
    public function testPrixTTC()
    {
        $calcul = new Calculs();
        $prixTTC = $calcul->calculTTC(10);
        $this->assertEquals(11.96,$prixTTC);
    }
}
````

Le principe consite à charger la classe, éxecuter la méthode à tester en passant des valeurs fictives, et comparer le résultat obtenu avec le résultat attendu. Il est possible d'exprimer d'autres tests qu'une égalité.

### Tester notre classe.

Ecire le teste qui permet de vérifier la méthode que vous avez écrit.


## Finaliser le projet

Une fois l'ensemble des concepts compris, vous veillerez à terminer la mise en place des tests sur l'ensemble de votre projet.


* Sécuritsation des parties aritcles et fournisseurs.
* Fixtures pour articles, fournissuers et user
* Tests de toutes les URL et fonctionnalités du site.
