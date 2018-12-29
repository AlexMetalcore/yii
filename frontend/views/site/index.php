<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use backend\models\Post;
$this->title = 'Веб заметки';
?>
<div class="site-index">
    <div class="about-me">
        <?=$about_me?>
    </div>
    <span class="new-posts">Новые статьи</span>
    <div class="container">
    <div class="row">
        <?php foreach ($posts as $post) : ?>
            <div class="col-md-4 block_border">
                <div class="content_post">
                    <?php if($post->img): ?>
                    <a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>" title="<?= $post->title;?>">
                        <?= Html::img('/admin/'.$post->img.'' ,
                            ['alt' => $post['title'] , 'class' => 'post_img']); ?></a>
                    <?php else: ?>
                        <a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>" title="<?= $post->title;?>">
                            <?= Html::img('/admin/images/no-img.png' ,
                                ['alt' => $post->title , 'class' => 'post_img']); ?></a>
                    <?php endif;?>
                    <span class="title">
                        <a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>"><?= strtoupper($post->title); ?></a>
                    </span>
                    <div class="content">
                        <?php if (strlen(Post::removeImgTags($post->content)) > 60): ?>
                            <?= mb_substr($post->content , 0 , 60 ). '...'; ?>
                        <?php else:?>
                            <?= Post::removeImgTags($post->content); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    </div>
</div>
