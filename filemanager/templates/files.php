<?php foreach($files as $file): ?>
<div class="file" data-editable="<?= $file->editable ?>" data-file="<?= $file->name ?>">
    <div class="thumbnail">
        <img src="<?= $file->thumbnail ?>" draggable="false">
    </div>
    <div class="filename">
        <?= $file->name ?>
    </div>
</div>
<?php endforeach; ?>