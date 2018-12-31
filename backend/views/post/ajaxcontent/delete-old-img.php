<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo Yii::$app->session->getFlash('success'); ?>
        <span class="count-old-img">Общее количество фото : <b><?=$count;?></b> </span>
        <span><?= $files_delete;?></span>
    </div>
<?php else:?>
<div class="alert alert-warning alert-dismissible" role="alert">
    Нету фото для удаления
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php endif;?>
