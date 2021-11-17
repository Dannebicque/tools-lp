# VueJS

Objectif : Mettre en place une application simple, basée sur VueJS, permettant d'afficher un catalogue, et de le gérer 
(DUTAF pour les MMI). Pour cela on va se baser sur un fournisseur d'API basé sur APIPlaform et 
[Symfony mis en place lors de la séance précédente](api.md). Vous devrez peut être modifier et adapter le code pour fournir les données nécessaires.

## Installation de VueJS

Le plus simple est de passer par [Vue-Cli](https://cli.vuejs.org/) (que vous avez peut être déjà installé sur la séance précédente).

Pour créer un nouveau projet, tapez la commande suivante :

```
vue create votre-projet
```

Et répondez aux différentes questions. Activez le "router" ([Vue Router](https://router.vuejs.org/)).

Vous pouvez sauvegarder le preset pour d'autres projets si besoin.

/!\ Vous pourriez aussi utiliser `vue ui` pour obtenir une interface graphique.

## Explications des fichiers et de la structure

## Premières manipulations.

Dans le composant App.vue qui est créé par défaut, et qui est le point d'entrée de votre application, nous allons faire les premiers tests.

Nous allons définir une liste de 3 éléments, par exemple :

```html
<ul>
  <li>Note sur Symfony : 19</li>
  <li>Note en intégration : 12</li>
  <li>Note en réseau : 14</li>
</ul>

```

Si on souhaite rendre cette liste "dynamique", on peut définir des variables pour les notes. La syntaxe est identique à Twig pour les variables...

```html
<ul>
  <li>Note sur Symfony : {{ symfony }}</li>
  <li>Note en intégration : {{ integration }}</li>
  <li>Note en réseau : {{ reseau }}</li>
</ul>

```

Si vous testez de nouveau ce code, vous aurez une erreur car les variables n'existent pas.
Nous devons les définir.

Pour cela, dans la partie javascript de notre fichier App.vue, nous allons définir une "entrée" data, qui va contenir nos valeurs.

La déclaration pourrait être :

```js
<script>
export default {
  name: 'App',
  data: () => {
    return {
      symfony: 6,
      integration: 2,
      reseau: 8,
    }
  },
}
</script>
```

### Exercice 

Ajoutez une quatrième ligne qui calcule la moyenne de ces 3 notes.

## Les boucles

Cette solution, fonctionnelle, n'est pas très pratique car se limite à 3 notes. On peut donc manipuler des objets ou tableaux. Pour cela, on va définir un objet notes, contenant plusieurs notes.

La déclaration d'un objet se fait de manière classique en javascript, dans la partie data de votre partie script. Exemple :

```js
<script>
export default {
  name: 'App',
  data: () => {
    return {
      notes: {
        "Symfony": 12,
        "Réseau": 14,
        "Intégration": 8,
        "Maths": 19,
        "Anglais": 12
      }
    }
  },
}
</script>
```

L'affichage avec une boucle, nommée [v-for](https://fr.vuejs.org/v2/guide/list.html) dans VueJs se ferait par exemple de la manière suivante :

```
<ul>
  <li v-for="(matiere, note) in notes" :key="matiere">Note sur {{matiere}} : {{ note }}</li>
</ul>
```

### Exercice

Calculez la moyenne des notes.
- Une solution consiste à faire de nouveau une boucle pour calculer la moyenne dans la partie "html", pas conseillé,
- Une autre solution consiste à utiliser les [Propriétés calculées](https://fr.vuejs.org/v2/guide/computed.html#Proprietes-calculees), en vous basant sur la documentation, proposez une méthode pour calculer la moyenne et afficher le résultat.

## Tests

Les tests se font avec l'instruction [v-if](https://fr.vuejs.org/v2/guide/conditional.html). 
Exemple, issue de la documentation :

```
<h1 v-if="awesome">Vue est extraordinaire !</h1>
```

Affichera le message selon la valeur de la variable "awesome". Concrétement, le titre H1 sera visible si awesome est vrai. Dans le cas contraire, rien ne s'affiche (et le code n'existe pas dans la source HTML !).

Il existe un v-else, et on pourrait écrire :

```
<h1 v-if="awesome">Vue est extraordinaire !</h1>
<h1 v-else>Oh non 😢</h1>
```

Le v-if (tout comme le v-for), peut s'appliquer sur toutes les balises HTML. Y compris la balise <template>. Les tests peuvent bien sûr être plus complexe que simplement une variable booléenne, et la syntaxe des conditions et des opérateurs est identique au javascript.
  
Il existe de manière assez identique l'instruction v-show, qui fonctionne de manière assez similaire, mais qui laisse le code apparaître dans la source HTML (masqué ou non (display:none) selon l'état de la condition).
  
Vous trouverez [sur ce lien](https://fr.vuejs.org/v2/guide/conditional.html#v-if-vs-v-show) des éléments d'explications sur comment et quand choisir v-if ou v-show.
  

## Créer des composants

Tout l'intéret des framework du type VueJs est la notion de composant, qui sont des "morceaux" de page, qui contiennent une logique (du HTML, du js et du CSS). 
Ces composants peuvent être très simplement réutilisés, en récupérant des "paramètres".

Exemple :

Composant "Bonjour"

```js
<template>
  <p>Bonjour</p>
</template>

<script>
export default {
  name: 'Bonjour'
}
</script>

<style scoped>
p {
  color: #42b983;
}
</style>
```

Et son utilisation dans une page (ou d'autres composants)

```js
...
<Bonjour />
...
```

Il est possible de passer des paramètres :

```js
<template>
  <p>{{ msg }}</p>
</template>

<script>
export default {
  name: 'Bonjour',
  props: {
    msg: String
  }
}
</script>

<style scoped>
p {
  color: #42b983;
}
</style>
```

Et son utilisation dans une page (ou d'autres composants)

```js
...
<Bonjour msg="Bonjour !" />
<Bonjour msg="VueJs C'est super" />
...
```

### Exercices

Créer des composantes pour :

* Un champ de formulaire de type "text" comprenant :
  * un label, une zone de saisie, et une classe, si le champ est obligatoire ou non
* Une liste déroulante de formulaire comprenant :
  * Un label, des données (tableau json), et une classe, si le champ est obligatoire ou non


## Installation d'Axios

On va utiliser la librairie Axios pour faire des requêtes à notre API (on pourrait utiliser Fetch).

La librairie se trouve ici : https://www.npmjs.com/package/axios

Et l'installation se fait avec la ligne suivante (en étant dans le répertoire de votre projet)

```
npm install axios
```

(vous pouvez utiliser Yarn).

## Créer des routes

### Principes

### Exercice

Créer les routes permettant de gérer les articles, les fournisseurs et la page d'acceuil. Créer les composants des différentes "pages" nécessaires.

## Repartir sur des bonnes bases.

Pour avoir la même base et la même solution, vous allez refaire un projet Symfony. On va utiliser la version "website" qui contient toutes les dépendances nécessaires.

```
composer create-project symfony/website-skeleton nomDuProjet
cd nomDuProjet
composer require api
cp .env .env.local
nano .env.local (modifier votre ligne de connexion à la BDD).
bin/console d:d:c (création de la BDD)
```


Créer deux entités.

- Category
 - Un libellé, texte, non null
 - Une couleur, texte, non null

- Message
 - Un titre, texte, non null
 - Une date de publication, datetime
 - Un message, longtext, non null
 - Une catégoie (ManytoOne)

Associez ces deux entités à ApiPlatform. Insérez quelques données dan la base de données (directement en phpMyAdmin), et testez que tout fonctionne avec Postman.

## Manipulation de l'API

Axios permet d'effectuer toutes les requêtes REST classique :
```
Extrait de la documentation
Request method aliases
For convenience aliases have been provided for all supported request methods.

axios.request(config)
axios.get(url[, config])
axios.delete(url[, config])
axios.head(url[, config])
axios.options(url[, config])
axios.post(url[, data[, config]])
axios.put(url[, data[, config]])
axios.patch(url[, data[, config]])
```

## Création d'un service

Une bonne pratique consiste à définir un fichier contenant nos "requêtes" Axios (ou appels à une API de manière général). Généralement un fichier par "entité".

Le fichier ci-dessous, permet d'executer quelques requêtes vers une entité Article de notre API

```js
import axios from 'axios'

const BASE_URL = 'http://localhost:8888/lpdev2021/public/index.php/api/articles'

async function getArticles()
{
    return await axios.get(BASE_URL);
}

async function getArticle(id)
{
  return await axios.get(BASE_URL+'/'+id);
}

async function postArticle(data)
{
  return await axios.post(BASE_URL, data);
}

export {getArticles, getArticle, postArticle}
```

L'interet est de n'avoir qu'une seule fois l'URL de notre API pour article, et donc de pouvoir rapidement mettre à jour. On pourrait même centraliser ces URL dans un seul et unique fichier. Ce fichier est à mettre dans un repertoire nommé services ou api, ...

### Exercices

En reprenant l'exemple ci-dessous.

Ecrire un fichier pour récupérer vos messages et vos catégories (ou équivalent avec le sujet "avancé").

Ces fichiers vont contenir les méthodes suivantes :
* Récupérer tous les éléments (get)
* Récupérer un élément par id (get)
* Créer un élément (post)
* Supprimer un élément par id (delete)
* Modifier un élément par id (put)

Dans un premier temps ajouter les méthodes dans les deux fichiers et ajuster les lignes avec axios pour gérer les différents cas.

## Récupérer les Catégories

Dans une page Categorie.vue importez le fichier js permettant de manipuler l'API.

Vous pourriez avoir une page de ce type :

```js
<template>
  <div class="about">
    <h1>Liste des Catégories</h1>
    ...Afficher les catégories ici ...
  </div>
</template>

<script>
import { getCategories } from '../services/categories';

export default {
  name: 'Categories',
  data () {
    return {
      categories: null
    }
  },
  async mounted () {
    this.categories = await getCategories()
  }
}
</script>
```

## Ajouter une catégorie

Ajouter une route et le composant associé afin de pouvoir ajouter une catégorie.
Utiliser les composants créés plus tôt pour faire un formulaire permettant de créer une nouvelle catégorie.

Les données des champs se font par l'intermédiaire des [v-model](https://vuejs.org/v3/guide/forms.html)

Il faut ensuite construire un objet que vous allez passer à votre méthode pour faire un ajout (post).

Exemple :

```js
methods: {
    addCategorie: function() {
      
      postCategorie({
        libelle: this.libelle
      })
    }
  }
```

**On pourra créer un composant pour afficher une catégorie**

### Exercice

Un fois que les catégories fonctionnent, faites de même pour les messages.
Un message dépend d'une catégorie, vous devez donc récupérer la liste des catégories pour alimenter une liste déroulante.
Pour ajouter les données avec APIPlatform, il faut passer l'URI comme "id" de la catégorie. APIPlatform se chargera de faire le lien entre les entités.

**On pourra créer un composant pour afficher un message**

## Mise à jour et suppression

En vous inspirant des pages précédentes, permettre la modification et la suppression d'un message et d'une catégorie.

## Page recherche

Sur le home, ajoutez la possibilité de faire une recherche et de récupère les messages correspondant.

Vous pourriez avoir besoin de créer une route spécifique dans ApiPlatform. Vous pouvez regarder dans cette partie de la documentation : https://api-platform.com/docs/core/filters/

/!\ Attention la documentation utilise les annotations PHP8. Ce qui n'est probablement pas votre cas. Vous devez transposer avec la notation "classique" de Symfony.
