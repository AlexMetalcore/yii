<?php

/* @var $this yii\web\View */
/* @var $posts [] */

use \backend\helper\HelperImageFolder;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Веб заметки';
?>
<div class="site-index">

    <span class="new-posts">
        Новые статьи
    </span>
    <div class="container">
    <div class="row">
        <?php foreach ($posts as $post) : ?>
            <div class="col-lg-4 col-md-4  col-sm-4 col-4 block_border">
                <div class="content_post">
                    <?php if($post->thumb_img): ?>
                    <a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>" title="<?= $post->title;?>">
                        <?= Html::img('/admin/'.$post->thumb_img.'' ,
                            ['alt' => $post['title'] , 'class' => 'post_img']); ?></a>
                    <?php else: ?>
                        <a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>" title="<?= $post->title;?>">
                            <?= Html::img('/admin/images/staticimg/no-img.png' ,
                                ['alt' => $post->title , 'class' => 'post_img']); ?></a>
                    <?php endif;?>
                    <span class="title">
                        <a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>"><?= strtoupper($post->title); ?></a>
                    </span>
                    <div class="content">
                        <?php if (strlen(HelperImageFolder::removeImgTags($post->content)) > 100): ?>
                            <?= mb_substr($post->content , 0 , 100 ). '...'; ?>
                        <?php else:?>
                            <?= HelperImageFolder::removeImgTags($post->content); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    </div>
</div>
