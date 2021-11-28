<?php
    require_once(dirname(__FILE__) . '/config/config.php');

    require_once(_CLASS_DIR_ . 'File.php');
    require_once(_CLASS_DIR_ . 'TextFile.php');
    require_once(_CLASS_DIR_ . 'FileFactory.php');

    require_once(_CLASS_DIR_ . 'FileManager.php');

    require_once(_HELPERS_DIR_ . 'template.php');
    
    $fileManager = new FileManager();

    if(isset($_GET['loadContent'])) {
        echo json_encode($fileManager->getFileContent(_FILES_DIR_, $_GET['loadContent']));
    } else {
        echo json_encode($fileManager->getDirContent(_FILES_DIR_));
    }