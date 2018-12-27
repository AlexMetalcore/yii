<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Все работы');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="portfolio-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Создать работу'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'title',
            'content:ntext',
            [
                'attribute' => 'img',
                'value' => function($model) {
                    return $model->img ? '<img src="/admin/'.$model->getMainImg ().'" height="200" width="300"/>' : 'фото нету';
                },
                'format'    => 'raw',
            ],
            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'buttons' => [
                    'view' => function ($url) {
                        return Html::a('', ['..'.$url] , ['class' => 'glyphicon glyphicon-eye-open' , 'title' => 'View']);
                    },
                    'update' => function ($url) {
                        return Html::a('', ['..'.$url] , ['class' => 'glyphicon glyphicon-pencil' , 'title' => 'Update']);
                    },
                    'delete' => function ($url) {
                        return Html::a('', ['..'.$url] , ['class' => 'glyphicon glyphicon-trash' , 'title' => 'Delete' , 'onClick' => 'return confirm("Вы хотите удалить данную запись?")']);

                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
