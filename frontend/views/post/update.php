<?php

use yii\helpers\Html;
use frontend\models\Category;

/* @var $this yii\web\View */
/* @var $model frontend\models\Post */

$this->title = 'Обновить запись: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Записи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="post-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'category' => $category,
        'authors' => $authors
    ]) ?>

</div>
