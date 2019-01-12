<?php

use yii\helpers\Html;
use frontend\controllers\PostController;

/* @var $this yii\web\View */
/* @var $model backend\models\Post */

$this->title = 'Создать статью';
$this->params['breadcrumbs'][] = ['label' => 'Записи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'category' => $category,
        'authors' => $authors
    ]) ?>
</div>
