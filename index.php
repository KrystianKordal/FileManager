<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager</title>
    <link rel="stylesheet" href="/assets/css/filemanager.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
    <div id="filemanager">
        <div class="filemanager-header">
            <div class='filemanager-title'>File Manager</div>
            <div class="filemanager-toolbar">
                <div class="toolbar-button" title="Upload File"><img src="/assets/img/upload.svg" id="toolbar_upload"></div>
            </div>
        </div>
        <div class="filemanager-content">
        </div>
        <div class="filemanager_overlay"></div>
        <div class="upload-file-modal">
            <div class="upload-file-modal-content">
                <div class="modal-close">
                    <img src="/assets/img/close.svg" class="modal-close-button">
                </div>
                <h2>Select file for upload</h2>
                <p><input type="file" id="upload_file_input"></p>
                <p><input type="submit" id="upload_file" class="btn btn-primary"></p>
            </div>

        </div>
    </div>
    <script src="/assets/js/filemanager.js"></script>
</body>
</html>