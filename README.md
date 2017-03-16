# Infos
- URL du site : [http://tenasmarre.thaledric.fr/TenAsMarreDeTonWallpaper/](http://tenasmarre.thaledric.fr/TenAsMarreDeTonWallpaper/)
- Notre branche de prod, c'est `master` : [https://github.com/Web-Imac-2017/TenAsMarreDeTonWallpaper/tree/master](https://github.com/Web-Imac-2017/TenAsMarreDeTonWallpaper/tree/master)  
# Mise en place
1. Le dossier du dépôt doit être placé de la sorte, relativement à Wamp : 
  `<wamp>/www/TenAsMarreDeTonWallpaper/` (sensible à la casse).  
2. Importer la BDD : `bdd_paysages.sql`, sur [notre Drive](https://drive.google.com/open?id=0B896bdQWjsjAU2labGplZmtkWmc).
3. Ensuite, il faut télécharger, dans le dossier `upload/`, le contenu du dossier `upload/` de notre Drive.
4. Pour établir la liaison avec la BDD : Faire une copie du fichier `DbConfigLayout.php` puis le renommer `DbConfig.php`.  
  Ensuite, modifier `DbConfig.php` pour qu'il utilise le host, nom de login, mot de passe et nom de BDD attendus.
5. `cd front && npm install && gulp`
6. ????????????
7. Profit
