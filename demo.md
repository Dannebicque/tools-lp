# Application de démonstration

Une application basique (DUTAF pour les MMI), est disponible sur le dépôt : [https://github.com/Dannebicque/dutafLP2020.git](https://github.com/Dannebicque/dutafLP2020.git)

Ce projet est composé de deux tables, d'une gestion des articles, des fournisseurs et d'une page d'accueil.

L'apparence n'a pas d'importance.

Récupérer le projet, configurer la base de donner, créer la table et les tables, ajouter quelques données. On pourrait écrire des fixtures afin de peupler la base de données avec des données fivtives [Documentation sur les fixtures](https://symfony.com/doc/2.0/bundles/DoctrineFixturesBundle/index.html).

Téléchargement du projet :

En vous placant dans votre répertoire www ou public_html :

````
git clone https://github.com/Dannebicque/dutafLP2020.git

cd dutafLP2020/
````

Mettre à jour le fichier .env, en le dupliquant .env.local et en adaptant vos informations.

Installer les vendors :

````
composer install
````

Installer la base de données

````
bin/console doctrine:database:create
bin/console doctrine:schema:update -f
````

Tester le site :

[http://localhost/dutafLP2020/public/index.php/](http://localhost/dutafLP2020/public/index.php/)

Adapter l'URL selon votre configuration.

Vous pouvez avoir les articles ou les fournisseurs avec les URL suivantes :

[http://localhost/dutafLP2020/public/index.php/](http://localhost/dutafLP2020/public/index.php/), [http://localhost/dutafLP2020/public/index.php/administration/produit/](http://localhost/dutafLP2020/public/index.php/administration/produit/) et 
[http://localhost/dutafLP2020/public/index.php/administration/fournisseur/](http://localhost/dutafLP2020/public/index.php/administration/fournisseur/)
