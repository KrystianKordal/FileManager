<?php foreach($files as $file): ?>
<div class="file">
    <div class="thumbnail">
        <img src="/assets/img/document.png">
    </div>
    <div class="filename">
        <?= $file->name ?>
    </div>
</div>
<?php endforeach; ?>