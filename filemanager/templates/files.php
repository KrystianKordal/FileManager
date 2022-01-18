<?php foreach($files as $file): ?>
<div class="file" data-file="<?= $file->dirName ?? $file->name ?>">
    <div class="thumbnail">
        <img src="<?= $file->thumbnail ?>" draggable="false">
    </div>
    <div class="filename">
        <?= $file->name ?>
    </div>
</div>
<?php endforeach; ?>