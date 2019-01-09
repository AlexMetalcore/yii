<?php

//namespace frontend\models;

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>"{summary}\n{items}\n{pager}",
        'summary' => 'Показано {count} из {totalCount} пользователей',
        'summaryOptions' => [
            'tag' => 'span',
            'class' => 'summary'
        ],
        'columns' => [
            'username',
            'email:email',
            'about:ntext',
            [
                    'header' => 'Статус',
                    'value'  => function($model){
                        switch ($model->status) {
                            case '20':
                                return 'Администратор';
                            case '15':
                                return 'Модератор';
                            case '10':
                                return 'Пользователь';
                        }
                    }
            ],
            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'buttons' => [
                    'delete' => function ($url) {
                        return Html::a('', ['..'.$url] , ['class' => 'glyphicon glyphicon-trash' , 'title' => 'Delete' , 'onClick' => 'return confirm("Вы хотите удалить пользователя?")']);

                    },
                ],
            ],
        ],
    ]); ?>
</div>
