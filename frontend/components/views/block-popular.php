<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 10.01.19
 * Time: 1:58
 */
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?=$menu?>
<b class="popular">Популярные статьи</b>
<?php foreach ($popular as $post): ?>
    <div class="block-popular-post">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 float-md-left">
                <a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>" title="<?= $post->title;?>">
                    <?= Html::img('/admin/'.$post->img.'' , ['alt' => $post->title , 'class' => 'post-widget']); ?>
                </a>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 block-name-date">
                <a href="<?= Url::to(['post/view' , 'id' => $post->id]);?>" class="popular-name"><?= $post->title;?></a>
                <span class="date">Дата: <?= $post->getDate(); ?></span>
            </div>
        </div>
    </div>
<?php endforeach;?>
