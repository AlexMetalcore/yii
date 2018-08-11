<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Записи', 'url' => ['index']];
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
                    'value' => '<img src="/admin/'.$model->img.'" height="200" width="300"/>',
                    'format'    => 'raw',
            ],
            'category.title',
            'author.username',
            'publish_status',
            'publish_date',
        ],
    ]) ?>


</div>
