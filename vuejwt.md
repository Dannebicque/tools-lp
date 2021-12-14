# Authentifier les échanges avec JWT

## Installer JWT du côté back
Installer et configurer JWT https://api-platform.com/docs/core/jwt/ (ou éventuellement : https://emirkarsiyakali.com/implementing-jwt-authentication-to-your-api-platform-application-885f014d3358) ou directement la document de [LexikJWTBundle](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#getting-started)

/!\ Une série de vidéo est sortie ApiPlatform : [Grafikart](https://grafikart.fr/formations/api-plaform)

Puis ensuite utiliser le Token dans vos échanges entre le front et le back.

## Tester avec postman, la bonne réception d'un token

Vous devez envoyer un username et un password à l'URL http://votredomaine/authentication_token (ou équivalent selon votre configuration)

Si l'authentifcation est réussie vous recevrez un token, à sauvegarder du coté client et à envoyer à chaque requête pour authentifier vos échanges.

## Mise en place depuis votre application

Avec Axios, le code pourrait ressembler au code ci-dessous


```js
axios({url: 'http://localhost:8888/ptutApi/public/index.php/authentication_token', data: {username:username, password:password}, method: 'POST' }) //username et password proviennent du formulaire
          .then(resp => {
            const token = resp.data.token
            const userData = atob(resp.data.token.split('.')[1]) //on récupère les données de l'utilisateur, par défaut, login, rôles
            localStorage.setItem('user', userData) // store the user in localstorage
            localStorage.setItem('usertoken', token) // store the token in localstorage
            router.push('/')
          })
          .catch(err => {
            localStorage.removeItem('user-token') // if the request fails, remove any possible user token if possible
          })
```

Il faut ensuite envoyer sur chaque requête du front vers le back, le token précédemment récupéré pour authentifier les échanges.

Par exemple avec Axios.

```js
axios.post(url, {
  headers: {
    'Authorization': `Bearer ` + localStorage.getItem('usertoken')
  }
})
```

## Exercice

Mettre en place et sécuriser les pages de gestion d'article et de fournisseur.
Regardez la documentation de vue-router pour permettre une verification au niveau de la route et autoriser l'affichage.

Notamment cette partie : https://router.vuejs.org/guide/advanced/navigation-guards.html#global-before-guards

Et cet extrait de code qui permet de vérifier si l'utilisateur est authentifié (présence d'un token par exemple).

```js
router.beforeEach((to, from, next) => {
  if (to.name !== 'Login' && !isAuthenticated) next({ name: 'Login' })
  else next()
})
```

## Mise en page

Tout comme pour du HTML/CSS "conventionnel", il existe des librairies pour rapidement mettre en forme votre application VueJS. La plus connue est probablement [Vuetify](https://vuetifyjs.com/en/) (basée sur le Material Design), mais vous pouvez aussi trouver [Boostrap-Vue](https://bootstrap-vue.org/docs). Ces deux libriaires proposent des composants avec une logique et une interactivité, mais aussi le CSS adapté.

Vous pourriez aussi installer directement bootstrap (ou autre), et créer vos propre composant sur la même logique.

Choisir l'une des librairies et personnaliser votre site web.

## Ressources

Diverses ressources en lien avec VueJS :

* https://awesome-vue.js.org/
* https://madewithvuejs.com/

