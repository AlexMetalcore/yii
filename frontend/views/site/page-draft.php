<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

?>
<div class="site-page-draft">


    <div class="alert alert-danger">
        Статья <span style="color: black;"><?= $post->title;?></span> находиться в черновике (<span style="color: red; font-weight: bold;"><?= $post->publish_status;?></span>)
    </div>
    Вернитесь <a class="history-back" onclick="history.back(); return false;">назад</a>
</div>