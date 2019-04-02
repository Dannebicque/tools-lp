# Notification avec Symfony

Le cours se base sur les supportd suivants :

* [Repo d'une démonstration réalisée au SymfonyCon de Lisbonne et de Paris](https://github.com/dunglas/symfonycon-lisbon)
* [Les slides associés](https://dunglas.fr/2019/03/symfonylive-paris-slides-symfony-on-steroids%e2%80%a8-vue-js-mercure-panther/)
* [Tuto sur Grafikart](https://www.grafikart.fr/tutoriels/symfony-mercure-1151)

## Principes

Ce système, basé sur Mercure va permettre à une application Symfony de transmettre des évènements à un hub qui se chargera 
ensuite de les transmettre aux périphériques qui y sont connectés. Autrement dit, le front, pourra recevoir des notifications 
(push) depuis le serveur (le back).

Pour réaliser cela, on va avoir besoin de [Mercure](https://github.com/dunglas/mercure) qui est un protocole qui permet d'envoyer des 
notifications push vers des navigateurs ou des clients connectés.
Cela va permettre d'avoir des applications temps-réels et une mise à jour des clients de manière rapide, fiable et 
(relativement) simple.

