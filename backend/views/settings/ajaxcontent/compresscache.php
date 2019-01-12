<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.01.19
 * Time: 18:55
 */
?>
<?php if (Yii::$app->session->hasFlash('compress')): ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo Yii::$app->session->getFlash('compress'); ?>
    </div>
<?php endif;?>
<?php if (Yii::$app->session->hasFlash('cache')): ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo Yii::$app->session->getFlash('cache'); ?>
    </div>
<?php endif;?>
