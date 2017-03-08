<?php

///////////////////////////////////////////////////////////
//                  ROUTES
///////////////////////////////////////////////////////////

// Exemple de route

// Router::connect(
//     'patate/:id/:ha',
//     'home/view/:id/:ha',
//     array(
//         'id' => '[0-9]+',
//         'ha' => '[a-z]+'
//     )
// );



// Index
Router::connect('/', 'welcome/index', array());

// User
Router::connect('/membre/add', 'membre/add', array());
Router::connect('/membre/login', 'membre/login', array());
Router::connect('/membre/logout', 'membre/logout', array());
Router::connect('/membre/getInfo', 'membre/getInfo', array());
Router::connect('/membre/edit', 'membre/edit', array());
Router::connect('/membre/get/:id', 'membre/get/:id', array('id' => '[0-9]+'));
Router::connect('/membre/delete/:id', 'membre/delete/:id', array('id' => '[0-9]+'));
Router::connect('/membre/ban/:id', 'membre/ban/:id', array('id' => '[0-9]+'));
Router::connect('/membre/changeRole/:id/:role', 'membre/changeRole/:id/:role', array('id' => '[0-9]+', 'role' => '[0-2]'));

// Wallpaper
Router::connect('/wallpaper/add', 'wallpaper/add', array());
Router::connect('/wallpaper/get/:id', 'wallpaper/get/:id', array('id' => '[0-9]+'));
Router::connect('/wallpaper/getAll', 'wallpaper/getAll', array());
Router::connect('/wallpaper/change/:id', 'wallpaper/change/:id', array('id' => '[0-9]+'));
Router::connect('/wallpaper/delete', 'wallpaper/delete', array());
Router::connect('wallpaper/getRandom/:limit', 'wallpaper/getRandom/:limit', array('limit' => '[0-9]+'))

// Question
Router::connect('/question/add', 'question/add', array());
Router::connect('/question/get/:id', 'question/get/:id', array('id' => '[0-9]+'));
Router::connect('/question/getAll', 'question/getAll', array());
Router::connect('/question/change/:id', 'question/change/:id', array('id' => '[0-9]+'));
Router::connect('/question/delete', 'question/delete', array());

// Catégorie
Router::connect('/categorie/add', 'categorie/add', array());
Router::connect('/categorie/get/:id', 'categorie/get/:id', array('id' => '[0-9]+'));
Router::connect('/categorie/getAll', 'categorie/getAll', array());
Router::connect('/categorie/change/:id', 'categorie/change/:id', array('id' => '[0-9]+'));
Router::connect('/categorie/delete', 'categorie/delete', array());

// Algo (non définitif, en attente des fonctions par David)
Router::connect('/algo/getQuestion', 'algo/getQuestion', array());
Router::connect('/algo/sendRep', 'algo/sendRep', array());
Router::connect('/algo/getWallpapers', 'algo/getWallpapers', array());