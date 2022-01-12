<?php
    define('_FMDEBUG_', true);

    if(_FMDEBUG_) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
    }

    define('_ROOT_DIR_', __DIR__ . '/');
    define('_APP_DIR_', _ROOT_DIR_ . 'filemanager/');
    define('_SRC_DIR_', _APP_DIR_ . 'src/');
    define('_CLASS_DIR_', _SRC_DIR_ . 'classes/');
    define('_HELPERS_DIR_', _SRC_DIR_ . 'helpers/');
    define('_TPL_DIR_', _APP_DIR_ . 'templates/');
    define('_FILES_DIR_', _ROOT_DIR_ . 'files/');

    define('_ASSETS_PATH_', '/assets/');
    define('_IMG_PATH_', _ASSETS_PATH_ . 'img/');
    define('_FILES_PATH_', '/files/');