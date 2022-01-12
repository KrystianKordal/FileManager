<?php
    require_once(dirname(__FILE__) . '/config.php');
    require_once(dirname(__FILE__) . '/vendor/autoload.php');
    
    $fileManager = new FileManager(_FILES_DIR_);

    if(isset($_GET['loadContent'])) {
        echo json_encode($fileManager->getFileContent($_GET['loadContent']));
    } else if(isset($_POST['saveFile'])) {
        echo json_encode($fileManager->saveFile($_POST['file'], $_POST['content']));
    } else if(isset($_POST['upload'])) {
        echo json_encode($fileManager->uploadFile($_FILES['file']));
    } else {
        echo json_encode($fileManager->getDirContent());
    }