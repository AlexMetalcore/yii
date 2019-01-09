<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.12.18
 * Time: 18:27
 */
use yii\helpers\Url;
use yii\bootstrap\Html;
?>

<?php foreach ($categories as $category): ?>
    <?php if(isset($count_posts[$category->title])):?>
        <div class="block-category">
            <a href="<?= Url::to(['category/view' , 'id' => $category->id]);?>"><?= $category->title;?></a>
            <span class="count_post">(<?= count($count_posts[$category->title]); ?>)</span>
        </div>
        <div class="post-data">
            <?php foreach ($count_posts[$category->title] as $post): ?>
                <div class="post-name">- <a href="<?= Url::to(['post/view' , 'id' => $post['post_id']]);?>"><?= ucfirst($post['post_name']);?></a></div>
            <?php endforeach;?>
        </div>
        <hr class="underline-block-post"/>
    <?php endif;?>
<?php endforeach; ?>

<b class="popular">Популярные статьи</b>
<?php foreach ($popular as $post): ?>
    <div class="block-popular-post">
        <div class="row">
            <div class="col-md-6">
                <a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>" title="<?= $post->title;?>">
                    <?= Html::img('/admin/'.$post->img.'' , ['alt' => $post->title , 'class' => 'post-widget']); ?>
                </a>
            </div>
            <div class="col-md-6 block-name-date">
                <a href="<?= Url::to(['post/view' , 'id' => $post->id]);?>" class="popular-name"><?= $post->title;?></a>
                <span class="date">Дата: <?= $post->getDate(); ?></span>
            </div>
        </div>
    </div>
<?php endforeach;?>