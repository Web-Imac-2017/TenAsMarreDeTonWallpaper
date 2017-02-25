# Cours IMAC 2 12/01/2017

## Pré-requis
* [node/npm](https://nodejs.org/)
  * Utilisé pour installer les packages.
* [gulp](https://github.com/gulpjs/)
  * Utilisé pour créer des taches d'automatisation, principallement pour build vos fichiers js et scss.
* [browserify](http://browserify.org/)
  * Permet de `require()` des modules npm et vos propres fichiers js.

## Fichiers
* __package.json__
  * Le fichier dans lequel les packages npm sont sauvegardés.
* __Gulpfile.js__
  * le fichier pour les tasks gulp.

## Dossiers
* __node_modules__
  * Généré par npm, c'est le dossier dans lequel les packages npm sont installés, à ajouter au .gitignore.
* __gulp__
  * Si vous voulez séparer les tasks gulp en fichiers, metez les dedans (ce n'est pas obligatoire, le nom est pas important).
* __src__
  * Metez vos fichiers source js et css dedans (le nom du dossier n'est pas important, il faut juste que ça corresponde avec le code dans les tasks Gulp).

## Commandes
```sh
$ # Ajouter un package npm au projet
$ npm install --save-dev nom_du_package
$ # ou
$ npm install --save nom_du_package
```
```sh
$ # Installer les packages présents dans packages.json
$ npm install
```
```sh
$ # Lancer les tasks Gulp
$ gulp
```

## Quelques packages npm utiles
* [__babel__](https://github.com/babel/babelify)
* Permet de générer des fichiers js compatibles avec tous les navigateurs, tout en codand en ES6.
* [__watchify__](https://github.com/substack/watchify)
  * Permet de watch les fichiers js et scss pour les re-compiler automatiquement.
* [__domready__](https://www.npmjs.com/package/domready)
  * Permet de lancer du code une fois le DOM prèt.
* [__isomorphic-fetch__](https://www.npmjs.com/package/isomorphic-fetch) et [__es6-promise__](https://www.npmjs.com/package/es6-promise)
  * Permet d'utiliser [fetch](https://developer.mozilla.org/fr/docs/Web/API/Fetch_API/Using_Fetch) ainsi que les [promises](https://developer.mozilla.org/fr/docs/Web/JavaScript/Reference/Objets_globaux/Promise) tout en étant compatible avec tous les navigateurs. Plus d'infos [ici](http://andrewhfarmer.com/ajax-libraries/).
* [__preload.js__](http://www.createjs.com/preloadjs)
  * Permet de gérer le chargement d'assets.
* [__gsap__](https://greensock.com/gsap)
  * Permet de faire des animations et timelines simplement.

Pour plus de packages, regardez le __packages.json__, ainsi que les différents fichiers Gulp. Faites attention aussi au fait que de nombreux packages possèdent des dépendances qu'il faut aussi installer si vous souhaiter les utiliser. Référez vous aux docs des packages.
