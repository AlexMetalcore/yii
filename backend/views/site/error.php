<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode(Yii::t('yii','Страницы не существует'))) ?>
    </div>
    <p>
        Вернитесь на главную <?= Html::a('Главная' , ['site/index'])?>.
    </p>

</div>
