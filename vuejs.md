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

```
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

```
...
<Bonjour msg="Bonjour !" />
<Bonjour msg="VueJs C'est super" />
...
```



