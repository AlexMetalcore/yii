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

<?php if(isset($count_posts[$category->id])): ?>
    <div class="block-category">
        <a href="<?= Url::to(['category/view' , 'id' => $category->id]);?>"><?= $category->title;?></a>
    <span class="count_post">(<?= count($count_posts[$category->id]); ?>)</span>
    </div>
    <div class="post-data">
        <?php foreach ($category->posts as $post):?>
        <?php if ($post->publish_status != 'draft'): ?>
                <div class="post-name">- <a href="<?= Url::to(['post/view' , 'id' => $post->id]);?>"><?= ucfirst($post->title);?></a></div>
            <?php else:?>
                <div class="post-name">- <a href="<?= Url::to(['post/view' , 'id' => $post->id]);?>"><?= ucfirst($post->title);?></a> <span style="color:red;">(<?=$post->publish_status; ?>)</span></div>
            <?php endif;?>
    <?php endforeach; ?>
    </div>
    <hr class="underline-block-post"/>
    <?php endif;?>

<?php endforeach; ?>


<b class="popular">Популярные статьи</b>
<?php foreach ($popular as $post): ?>
    <a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>" title="<?= $post->title;?>">
        <?= Html::img('/admin/'.$post->img.'' ,
            ['alt' => $post->title , 'class' => 'post-widget']); ?></a>
    <a href="<?= Url::to(['post/view' , 'id' => $post->id]);?>" class="popular-name"> - <?= $post->title;?></a>
<?php endforeach;?>
