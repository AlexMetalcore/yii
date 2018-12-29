<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1>Список категорий</h1>

    <p>
        <?= Html::a('Создать категорию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>"{summary}\n{items}\n{pager}",
        'summary' => 'Показано {count} из {totalCount} категорий',
        'summaryOptions' => [
            'tag' => 'span',
            'class' => 'summary'
        ],
        'columns' => [
            'title',
            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Действия'
            ],
        ],
    ]); ?>
</div>
