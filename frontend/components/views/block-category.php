<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12.12.18
 * Time: 18:27
 */
use yii\helpers\Url;
?>
<?php foreach ($data_category as $category): ?>
    <div class="block-category">
        <a href="<?= Url::to(['category/view' , 'id' => $category->id]);?>"><?= $category->title;?></a>
    <span class="count_post">(<?= count($category->posts); ?>)</span>
    </div>
    <div class="post-data">
        <?php foreach ($category->posts as $post):?>
                <div class="post-name">- <a href="<?= Url::to(['post/view' , 'id' => $post->id]);?>"><?= ucfirst($post->title);?></a></div>
    <?php endforeach; ?>
    </div>
<?php endforeach; ?>
