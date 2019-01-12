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
    <li>
        <?php if (isset($category['title']) && $category['title']): ?>
        <div class="block-category">
            <a href="<?= \yii\helpers\Url::to(['category/view', 'id' => $category['id']]) ?>" id="mainMenu">
                <?= $category['title']; ?>
            </a><span class="count_post"><?= isset($count_posts[$category['title']]) ? '('.count($count_posts[$category['title']]).')' : '';?></span>
        </div>
            <hr class="underline-block-post"/>
        <?php if(isset($category['childs']) ): ?>
            <ul class="subMainMenu">
                <?=$this->getMenuHtml($category['childs'])?>
            </ul>
        <?php endif;?>
        <?php endif; ?>
    </li>