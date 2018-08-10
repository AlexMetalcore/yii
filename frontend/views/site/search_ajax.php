<?php
use yii\helpers\Url;
?>
<?php if ($posts): ?>
<?php foreach ($posts as $post): ?>
        <a href="<?= Url::to(['post/view' , 'id' => $post->id]); ?>" title="<?= $post->title;?>">
            <li><?=$post->title;?></li></a>
<?php endforeach; ?>
<?php endif; ?>
