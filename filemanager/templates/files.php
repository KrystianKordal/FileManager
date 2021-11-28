<?php foreach($files as $file): ?>
<div class="file">
    <div class="thumbnail">
        <img src="<?= $file->thumbnail ?>" draggable="false">
    </div>
    <div class="filename">
        <?= $file->name ?>
    </div>
</div>
<?php endforeach; ?>