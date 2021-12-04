<div class="edit-file">
    <textarea id="file_content"><?= $content ?></textarea>
    <div class="pull-right button-container">
    <input type="button" id="back_from_edit" class="btn btn-secondary" value="Back">
        <input type="button" id="save_file" class="btn btn-primary" value="Save" data-file="<?= $file->name ?>">
    </div>
</div>