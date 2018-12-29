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
                        return $model->status == 20 ? 'Администратор' : 'Пользователь';
                    }
            ],
            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
            ],
        ],
    ]); ?>
</div>
