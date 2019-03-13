# Debugage et qualité de code

Le debugage d'une application peut être une tâche complexe à mener. Il existe en fait plusieurs niveaux d'erreurs, chacun impliquant une méthode et des "astuces" ou "outils" différents. On peut identifier :

1. Les erreurs de syntaxe
2. Les problèmes d'executions
3. Les erreurs en production

## Les erreurs de syntaxe

Les erreurs de syntaxe sont prabablement les erreurs les plus simples à corriger. En effet, si vous oubliez un ";", si vous faite une erreur de frappe sur un nom de méthode, de variable, de classe, un IDE va le détecter pendant que vous écrivez le code et vous proposera généralement de les corriger.

Attention toutefois aux erreurs de typo sur vos variables. L'IDE s'assure simplement que la varibale existe, et qu'elle est du type adequat. Pas que c'est la bonne variable selon votre contexte.

## Les problèmes d'executions

Ce type d'erreur se produit lorsque vous tesez votre application. Il n'y a, a priori, plus d'erreurs de syntaxes, mais vous n'obtenez pas le comportement attendu.

Ce type d'erreur peut être compliqué à débugger car il va impliquer de bien comprendre ce que fait votre code et pourquoi le résultat est erroné.

Avec les framework moderne, il existe généralement des outils (le profiler de symfony par exemple), qui vont vous aider à débugguer et comprendre le dysfonctionnement. Ces outils retracent l'ensemble du chemin parcouru par l'application pour répondre à votre requète.

Si cette étape ne suffit pas, ou n'est pas suffisament précise (ou difficile à interpréter), il va falloir mettre en place votre propre mécanisme de débugage en utilsant des astuces pour voir le comportement de votre code.

Cela peut simplement consister à mettre des "echo" pour identifier le cheminenement, est ce que mon test s'execute ? Combien de fois est executée ma boucle ? Mais aussi voir ce que contient vos variables en utilisant notamment l'instruction dump (ou var_dump) qui permet d'afficher le contenu d'une variable sans se soucier de son type.

## Les erreurs en production

https://afsy.fr/avent/2017/22-log-me-tender


# Qualité de code
