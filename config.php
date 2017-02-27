<?php

error_reporting(E_ALL);

// Some constants to use EVERYWHERE !

// Server Root Location
define('HOSTROOT', $_SERVER['DOCUMENT_ROOT']);
define('MAINDIR', 'TenAsMarreDeTonWallpaper/');
define('ROOT', HOSTROOT . '/' . MAINDIR);

// Directories
define('CONTROLLER_DIR', 'controllers/');
define('MODEL_DIR', 'models/');

// Kernel
define('KERNEL', ROOT . 'kernel/');

// Controllers default values
define('DEFAULT_CONTROLLER', 'home');
    // Errors
define('ERROR_CONTROLLER', 'error');
    // Layout
define('LAYOUT_CONTROLLER', 'layout');

// Actions default values
define('DEFAULT_ACTION', 'defaultAction');
    // Errors handlers
define('ACTION_404', 'error404');

// Base Url for internal links
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/' . MAINDIR);
define('BASE_URL_S', 'https://' . $_SERVER['HTTP_HOST'] . '/' . MAINDIR);