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
Router::connect('/user/add', 'user/add', array());
Router::connect('/user/login', 'user/login', array());
Router::connect('/user/logout', 'user/logout', array());
Router::connect('/user/get/:id', 'user/get/:id', array('id' => '[0-9]+'));
Router::connect('/user/delete/:id', 'user/delete/:id', array('id' => '[0-9]+'));
Router::connect('/user/ban/:id', 'user/ban/:id', array('id' => '[0-9]+')));
Router::connect('/user/change/:id', 'user/change/:id', array('id' => '[0-9]+')));

// Wallpaper
Router::connect('/wallpaper/add', 'wallpaper/add', array());
Router::connect('/wallpaper/get/:id', 'wallpaper/get/:id', array('id' => '[0-9]+'));
Router::connect('/wallpaper/getAll', 'wallpaper/getAll', array());
Router::connect('/wallpaper/change/:id', 'wallpaper/change/:id', array('id' => '[0-9]+'));
Router::connect('/wallpaper/delete', 'wallpaper/delete', array());

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



