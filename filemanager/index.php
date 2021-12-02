<?php
    require_once(dirname(__FILE__) . '/config/config.php');

    require_once(_CLASS_DIR_ . 'File/File.php');
    require_once(_CLASS_DIR_ . 'File/TextFile.php');
    require_once(_CLASS_DIR_ . 'File/FileFactory.php');

    require_once(_CLASS_DIR_ . 'FileManager.php');

    require_once(_CLASS_DIR_ . 'FMError.php');

    require_once(_HELPERS_DIR_ . 'template.php');
    
    $fileManager = new FileManager(_FILES_DIR_);

    if(isset($_GET['loadContent'])) {
        echo json_encode($fileManager->getFileContent($_GET['loadContent']));
    } else if(isset($_POST['saveFile'])) {
        echo json_encode($fileManager->saveFile($_POST['file'], $_POST['content']));
    } else {
        echo json_encode($fileManager->getDirContent());
    }