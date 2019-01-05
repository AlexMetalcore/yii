<?php
/*  @var $this yii\web\View
/* @int $count
 * @array $files_delete
*/
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo Yii::$app->session->getFlash('success'); ?>
        <span class="count-old-img">Общее количество : <b><?=$count;?></b> </span>
        <span><?= $files_delete;?></span>
    </div>
<?php endif;?>
