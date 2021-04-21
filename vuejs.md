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

## Installation d'Axios

On va utiliser la librairie Axios pour faire des requêtes à notre API (on pourrait utiliser Fetch).

La librairie se trouve ici : https://www.npmjs.com/package/axios

Et l'installation se fait avec la ligne suivante (en étant dans le répertoire de votre projet)

```
npm install axios
```

(vous pouvez utiliser Yarn).

## Explications des fichiers et de la structure

## Créer des routes

### Principes

### Exercice

Créer les routes permettant de gérer les articles, les fournisseurs et la page d'acceuil. Créer les composants des différentes "pages" nécessaires.

## Créer des composants

Tout l'intéret des framework du type vueJs est la notion de composant, qui sont des "morceaux" de page, qui contiennent une logique (du HTML, du js et du CSS). 
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
  * Un label, des données, et une classe, si le champ est obligatoire ou non

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

### Exercices

En reprenant l'exemple vue avec Frédéric et André, on va écrire un fichier par "entité" de notre partie back.

un fichier fournisseur.js et article.js à mettre "services".
Ces fichiers vont contenir les méthodes suivantes :
* Récupérer tous les éléments (get)
* Récupérer un élément par id (get)
* Créer un élément (post)
* Supprimer un élément par id (delete)
* Modifier un élément par id (put)

Dans un premier temps ajouter les méthodes dans les deux fichiers et ajuster les lignes avec axios pour gérer les différents cas.

## Récupérer les fournisseurs

Dans votre page Fournisseur.vue importez le fichier js permettant de manipuler l'API.

Vous pourriez avoir une page de ce type :

```js
<template>
  <div class="about">
    <h1>Liste des fournisseurs</h1>
    ...Afficher les fournisseurs ici ...
  </div>
</template>

<script>
import { getFournisseurs } from '../services/fournisseur';

export default {
  name: 'Fournisseurs',
  data () {
    return {
      fournisseurs: null
    }
  },
  async mounted () {
    this.fournisseurs = await getFournisseurs()
  }
}
</script>
```

## Ajouter un fournisseur

Ajouter une route et le composant associé afin de pouvoir ajouter un fournisseur.
Utiliser les composants créés plus tôt pour faire un formulaire permettant de créer un nouveau fournisseur.

Les données des champs se font par l'intermédiaire des [v-model](https://vuejs.org/v2/guide/forms.html)

Il faut ensuite construire un objet que vous allez passer à votre méthode pour faire un ajout (post).

Exemple :

```js
methods: {
    addFournisseur: function() {
      
      postFournisseur({
        libelle: this.libelle,
        ville: this.ville,
        telephone: this.telephone,
      })
    }
  }
```
**On pourra créer un composant pour afficher un fournisseur**

### Exercice

Un fois que les fournisseurs fonctionnent, faites de même pour les articles.
Un article dépend d'un fournisseur, vous devez donc récupérer la liste des fournisseurs pour alimenter une liste déroulante.
Pour ajouter les données avec APIPlatform, il faut passer l'URI comme "id" du fournisseur. APIPlatform se chargera de faire le lien entre les entités.

**On pourra créer un composant pour afficher un article**

## Mise à jour et suppression

En vous inspirant des pages précédentes, permettre la modification et la suppression d'un fournisseur et d'un article.

## Page recherche

Sur le home, ajoutez la possibilité de faire une recherche et de récupère les articles inférieurs à un prix.

Vous pourriez avoir besoin de créer une route spécifique dans ApiPlatform. Vous pouvez regarder dans cette partie de la documentation : https://api-platform.com/docs/core/filters/

/!\ Attention la documentation utilise les annotations PHP8. Ce qui n'est probablement pas votre cas. Vous devez transposer avec la notation "classique" de Symfony.
