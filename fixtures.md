# Professionnaliser ses tests

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
## Comment écrire un script pour automatiser les tests
## Comment mettre en place une sécurité dans Symfony

### Rappels sur la partie sécurité de Symfony

### Mise en place sur notre exemple

## Comment tester la partie sécurisée avec PHPUnit.

## Les concepts de l'injection de dépendance.

### Concepts de l'Injection de dépendances
### Ecire une classe de traitement

## Les tests unitaires pour valider nos classes et leurs méthodes.

### Concepts

### Tester notre classe.
