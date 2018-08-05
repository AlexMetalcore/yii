<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Блог по программированию';
?>
<div class="site-index">
    <div class="container">
    <div class="row">
        <?php foreach ($posts as $post) : ?>
            <div class="col-md-4 block_border">
                <div class="content_post">
                    <?php if($post['img']): ?>
                    <a href="<?= Url::to(['post/view' , 'id' => $post['id']]); ?>" title="<?= $post['title'];?>">
                        <?= Html::img('@web/'.$post['img'].'' ,
                            ['alt' => $post['title'] , 'class' => 'post_img']); ?></a>
                    <?php else: ?>
                        <a href="<?= Url::to(['post/view' , 'id' => $post['id']]); ?>" title="<?= $post['title'];?>">
                            <?= Html::img('@web/images/no-img.png' ,
                                ['alt' => $post['title'] , 'class' => 'post_img']); ?></a>
                    <?php endif;?>
                    <span class="title">
                        <a href="<?= Url::to(['post/view' , 'id' => $post['id']]); ?>"><?= strtoupper($post['title']); ?></a>
                    </span>
                    <div class="content">
                        <?= $post['content']; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    </div>
</div>
