<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28.12.18
 * Time: 3:02
 */
use yii\helpers\Html;
?>
<div class="container-fluid" id="fix-margin">
    <div class="item-content-portfolio">
        <div class="row">
            <div class="col-md-8">
            <?php foreach (explode(',' , $content->img) as $img): ?>
            <div class="col-md-12">
                <?= Html::img('/admin/'.$img.'' , ['class' => 'portfolio-img']) ?>
            </div>
            <?php endforeach;?>
            </div>
            <div class="col-md-4">
                <?= Html::img('/admin/images/delete.png' , ['class' => 'portfolio-close']) ?>
                <span class="name-work"><?=$content->title;?></span>
                <div class="content-item">
                    <?= $content->content;?>
                </div>
            </div>
        </div>
    </div>
</div>
