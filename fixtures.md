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

## Les concepts de l'injection de dépendance.

### Concepts de l'Injection de dépendances

### Ecrire une classe de traitement

## Les tests unitaires pour valider nos classes et leurs méthodes.

### Concepts

### Tester notre classe.
