# Notification avec Symfony

Le cours se base sur les supportd suivants :

* [Documentation de Symfony](https://symfony.com/doc/current/mercure.html)
* [Document de Mercure](https://mercure.rocks/)
* [Repo d'une démonstration réalisée au SymfonyCon de Lisbonne et de Paris](https://github.com/dunglas/symfonycon-lisbon)
* [Les slides associés](https://dunglas.fr/2019/03/symfonylive-paris-slides-symfony-on-steroids%e2%80%a8-vue-js-mercure-panther/)
* [Tuto sur Grafikart](https://www.grafikart.fr/tutoriels/symfony-mercure-1151)

## Principes

Ce système, basé sur Mercure va permettre à une application Symfony de transmettre des évènements à un hub qui se chargera 
ensuite de les transmettre aux périphériques qui y sont connectés. Autrement dit, le front, pourra recevoir des notifications 
(push) depuis le serveur (le back).

Pour réaliser cela, on va avoir besoin de [Mercure](https://github.com/dunglas/mercure) qui est un protocole qui permet d'envoyer des notifications push vers des navigateurs ou des clients connectés.
Cela va permettre d'avoir des applications temps-réels et une mise à jour des clients de manière rapide, fiable et 
(relativement) simple.

## Installer Mercure

Rendez-vous sur [GitHub](https://github.com/dunglas/mercure/releases) pour récupérer la dernière version de Mercure selon votre OS.

Pour les utilisateurs de Windows, il exite l'executable. Pour linux les dépendances nécessaires. Dans tous les cas vous avez les sources que vous pouvez compiler pour obtenir le fichier "mercure" executable.

### pour MacOS
*Nota* Pour MacOs, choisir la version "Darwings" correspondante à votre processeur (32 (i386) ou 64 bits). Vous devrez probablement autoriser dans la sécurité l'execution de merci.

*Nota* Pour les utilisateurs de MacOs, il faut installer Go (le langage de programmation), [avec Brew par exemple](http://sourabhbajaj.com/mac-setup/Go/README.html), et ensuite compiler les sources :

````
git clone https://github.com/dunglas/mercure
cd mercure
GO111MODULE=on go get
GO111MODULE=on go build
````

### Pour Windows

Il faut créer 4 variables d'environnements :

* JWT_KEY avec votre clé secrète
* ADDR avec ":3000"
* ALLOW_ANONYMOUS avec "1"
* CORS_ALLOWED_ORIGINS avec "*"

Puis executer uniquement **mercure** dans votre console.

## Executer Mercure

Mercure est un petit service qui doit tourner sur votre serveur (comme un node par exemple). Pour cela vous devez executer la commande suivante :

````
./mercure --jwt-key='!ChangeMe!' --addr=':3000' --debug --allow-anonymous --cors-allowed-origins='*' --publish-allowed-origins='http://localhost:3000'
````

n'oubliez pas de mettre à jour le chemin de votre executable, et idéalement de mettre une vrai clé pour le JWT token. Par défaut avec cette ligne, le serveur mercure sera accessible sur l'adresse  [http://localhost:3000](http://localhost:3000). Vous pouvez vous rendre sur cette page, vous devriez voir :

**Welcome to Mercure!**

[!mercure.png]

## Tester Mercure

Il est possible de tester mercure assez rapidement, en tout cas de s'assurer qu'il peut communiquer. On va donc envoyer une requète au serveur mercure et observer la réponse.

Pour cela il faut envoyer une requête à l'adresse http://localhost:3000/.well-known/mercure, avec une méthode **POST**.

On va utiliser PostMan une nouvelle fois pour ce test.

Saisir l'adresse et la méthode. Pour que Mercure fonctionne, on doit générer un token avec JWT. On se rend sur [jwt.io](https://jwt.io/) pour générer ce token.

On va mettre dans la partie **PAYLOAD**

````
{
  "mercure": {
  "publish": ["*"]
  }
}
````

L'étoile dans publish permet de dire qu'on envoie à tout le monde. 

Dans la partie **VERIFY SIGNATURE** vous devez inscrire la clé utilisée avec Mercure (*!ChangeMe!* dans l'exemple ci-dessus).

Et vous pouvez récupérer le token dans la partie de gauche de JWT.io.
Dans l'onglet "authorization" de Postman, vous devez coller dans la case token, le token généré par JWT. 
Dans la partie "body", en choisissant "x-www-form-urlencoded" vous pouvez ajouter les éléments à envoyer.

* le champ **topic** (obligatoire) avec comme valeur, normalement une URL (l'adresse du message à lire par Mercure, et pour laquelle les clients front pourront s'abonner). L'adresse n'a donc pas besoin de réellement exister à ce stade.
* Un champ **data** avec une valeur au hasard pour s'assurer du bon fonctionnement.

En cliquant sur "send" dans postman, vous devriez vous un code apparaitre dans body. Et dans la console executant mercure une nouvelle ligne, indiquant la mise à jour et l'arrivée d'un message.

Et voilà ! Vous venez de faire votre premier échange avec Mercure.

## Récupérer le message dans le front.

Dans la partie front, il faut lui dire d'écouter le serveur Mercure, de s'abonner à des topics pour en recevoir les données à chaque "push".

Pour ce premier test, vous pouvez ecrire un simple fichier index.html, que vous executez avec votre serveur local.

````
<script>
    const url = new URL('http://localhost:3000/.well-known/mercure'); //adresse de votre serveur mercure
    url.searchParams.append('topic', 'http://monsite.com/ping'); //abonnement au topic (URL mise dans postman)
    
    const eventSource = new EventSource(url); //on déclenche l'écoute (non compatible IE et Edge)

    eventSource.onmessage = e => console.log(e); // A chaque message envoyé à mercure, celui ci est diffusé à tous les clients écoutant
</script>
````

Si vous envoyez de nouveau depuis postman la commande, vous devriez vous apparaître le message dans la console.

````
MessageEvent {isTrusted: true, data: "test", origin: "http://localhost:3000", lastEventId: "16d7601e-661b-4a86-80cf-cfafdb756c5b", source: null, …}
bubbles: false
cancelBubble: false
cancelable: false
composed: false
currentTarget: EventSource {url: "http://localhost:3000/.well-known/mercure?topic=http%3A%2F%2Fmonsite.com%2Fping", withCredentials: false, readyState: 1, onopen: null, onmessage: ƒ, …}
data: "test"
defaultPrevented: false
eventPhase: 0
isTrusted: true
lastEventId: "16d7601e-661b-4a86-80cf-cfafdb756c5b"
origin: "http://localhost:3000"
path: []
ports: []
returnValue: true
source: null
srcElement: EventSource {url: "http://localhost:3000/.well-known/mercure?topic=http%3A%2F%2Fmonsite.com%2Fping", withCredentials: false, readyState: 1, onopen: null, onmessage: ƒ, …}
target: EventSource {url: "http://localhost:3000/.well-known/mercure?topic=http%3A%2F%2Fmonsite.com%2Fping", withCredentials: false, readyState: 1, onopen: null, onmessage: ƒ, …}
timeStamp: 6742.265000008047
type: "message"
userActivation: null
__proto__: MessageEvent
````

Toutes les datas se trouvent dans data. On peut les récupérer dans un format JSON manipulation par le front en écrivant :

````
eventSource.onmessage = e => {
    var datajson = JSON.parse(e.data) //si data est un JSON correctement formaté, sinon simple console.log(e.data)
    console.log(datajson);
}
````
    
 ## Utilisation avec Symfony
 
 Il faut installer un bundle pour utiliser Mercure avec Symfony, et pouvoir envoyer des notifications à Mercure, qui seront ensuites envoyées aux différents clients connectés.
 
 ````
 composer req mercure
 ````
 
 Il faut configurer le fichier .env.local avec l'URL du serveur mercure et le token (généré depuis JWT.io).
 
 Si on souhaite par exemple informer tous les utilisateurs de notre DUTAF de l'apparition d'un nouveau produit, le code pourrait être le suivant :
 
 ````
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

public function new(Publisher $publisher, Request $request): Response
    {
        ...
        if ($form->isSubmitted() && $form->isValid()) {
            ...
            $update = new Update(
                'http://example.com/fournisseur/1', //topic (URL) sur laquelle s'abonner
                json_encode(['nom' => $fournisseur->getNom(),
                             'telephone' => $fournisseur->getTelephone(),
                             'ville' => $fournisseur->getVille(),
                    ])
            );

            // The Publisher service is an invokable object
            $publisher($update);

            return $this->redirectToRoute('fournisseur_index');
        }

       ...
       
    }
 ````


L'objet Update prend 3 paramètres :

* Le topic (sous forme d'URL)
* Les datas (au format JSON)
* La cible (optionnel)

Si vous modifiez le fichier html de test précédent, en écoutant le topic : http://example.com/fournisseur/1 vous devriez voir les données de votre fournisseur.

## A vous de jouer

Proposer une application (soit une nouvelle installation de Symfony, soit en complétant le projet en cours) qui permet d'avoir une salle de Tchat avec plusieurs utilisateurs.
* Dans un premier temps on peut simplement considérer que tous les utilisateurs visualisent tous les messages (tchat public).
* Ajouter une liste des personnes connectées

## Authentifier le destinataire

Le principe consiste à définir un cookie pour chaque session (dépendant par exemple de l'utilisateur connecté), puis de passer ce cookie dans les échanges afin d'authentifier le client.

A venir...
