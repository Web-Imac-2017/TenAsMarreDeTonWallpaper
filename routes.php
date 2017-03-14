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
Router::connect('/membre/getAll', 'membre/getAll', array());
Router::connect('/membre/edit', 'membre/edit', array());
Router::connect('/membre/get/:id', 'membre/get/:id', array('id' => '[0-9]+'));
Router::connect('/membre/delete', 'membre/delete', array());
Router::connect('/membre/ban/:id', 'membre/ban/:id', array('id' => '[0-9]+'));
Router::connect('/membre/changeRole/:id/:role', 'membre/changeRole/:id/:role', array('id' => '[0-9]+', 'role' => '[0-2]'));

// Wallpaper
Router::connect('/wallpaper/add', 'wallpaper/add', array());
Router::connect('/wallpaper/get/:id', 'wallpaper/get/:id', array('id' => '[0-9]+'));
Router::connect('/wallpaper/random/:nb', 'wallpaper/random/:nb', array('nb' => '[0-9]+'));
Router::connect('/wallpaper/getMines/:nb', 'wallpaper/getMines/:nb', array('nb' => '[0-9]+'));
Router::connect('/wallpaper/getByCategorie/:id', 'wallpaper/getByCategorie/:id', array('id' => '[0-9]+'));
Router::connect('/wallpaper/getMostDL/:nb', 'wallpaper/getMostDL/:nb', array('nb' => '[0-9]+'));
Router::connect('/wallpaper/getMostAP/:nb', 'wallpaper/getMostAP/:nb', array('nb' => '[0-9]+'));
Router::connect('/wallpaper/latest/:nb', 'wallpaper/latest/:nb', array('nb' => '[0-9]+'));
Router::connect('/wallpaper/delete/:id', 'wallpaper/delete/:id', array('id' => '[0-9]+'));

// Question
Router::connect('/question/add', 'question/add', array());
Router::connect('/question/get/:id', 'question/get/:id', array('id' => '[0-9]+'));
Router::connect('/question/getAll', 'question/getAll', array());
Router::connect('/question/delete', 'question/delete', array());
Router::connect('/question/latest/:nb', 'question/latest/:nb', array('nb' => '[0-9]+'));

// Catégorie
Router::connect('/categorie/add', 'categorie/add', array());
Router::connect('/categorie/get/:id', 'categorie/get/:id', array('id' => '[0-9]+'));
Router::connect('/categorie/getAll', 'categorie/getAll', array());
Router::connect('/categorie/delete', 'categorie/delete', array());
Router::connect('/categorie/change', 'categorie/change', array());

// Algo (Conversation)
Router::connect('/algo/getFirstQuestion', 'algo/getFirstQuestion', array());
Router::connect('/algo/getNextQuestion/:reponse', 'algo/getNextQuestion/:reponse', array('reponse' => '[0-4]+'));
Router::connect('/algo/currentQuestion', 'algo/currentQuestion', array());
Router::connect('/algo/restart', 'algo/restart', array());
Router::connect('/algo/undo', 'algo/undo', array());
Router::connect('/algo/updateDL/:id', 'algo/updateDL/:id', array('id' => '[0-9]+'));