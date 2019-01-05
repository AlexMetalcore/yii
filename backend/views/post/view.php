<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">

    <h1>Название статьи: <?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы хотите удалить данную запись?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'anons:html',
            'content:html',
            [
                    'attribute' => 'img',
                    'value' => $model->img ? '<img src="/admin/'.$model->img.'" width="25%"/>' : 'фото нету',
                    'format'    => 'raw',
            ],
            'category.title',
            'author.username',
            [
                    'attribute' => 'publish_status',
                    'header' => 'Статус статьи',
                    'value'  => function($model) {
                        return $model->publish_status == 'publish' ? 'Опубликовано' : 'Черновик';
                    },
            ],
            'publish_date',
        ],
    ]) ?>


</div>
