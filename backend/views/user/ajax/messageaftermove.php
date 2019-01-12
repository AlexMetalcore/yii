<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 11.01.19
 * Time: 21:16
 */
?>
<?php if (Yii::$app->session->hasFlash('data-move')): ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo Yii::$app->session->getFlash('data-move'); ?>
    </div>
<?php endif;?>
<?php if (Yii::$app->session->hasFlash('delete-data-user')): ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo Yii::$app->session->getFlash('delete-data-user'); ?>
    </div>
<?php endif;?>
<?php if (Yii::$app->session->hasFlash('delete-user')): ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo Yii::$app->session->getFlash('delete-user'); ?>
    </div>
<?php endif;?>
