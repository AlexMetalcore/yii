<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Post;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Записи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать запись', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout'=>"{summary}\n{items}\n{pager}",
            'summary' => 'Показано {count} из {totalCount} статтей',
            'summaryOptions' => [
                'tag' => 'span',
                'class' => 'summary'
            ],
            'columns' => [
                'title',
                [
                        'header' => 'Описание',
                        'value'  => function($model){
                            return strlen(Post::removeImgTags($model->content)) < 200 ? $model->content : mb_substr($model->content , '0' , 200).'...';
                        },
                        'format' => 'raw'
                ],
                [
                        'header' => 'Категория',
                        'value' => 'category.title',
                ],
                [
                    'header' => 'Автор',
                    'value' => 'author.username',
                ],
                [
                    'header' => 'Статус',
                    'value'  => function($model){
                        return $model->publish_status == 'publish' ? 'Опубликовано' : 'Черновик';
                    }
                ],
                'publish_date',
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
</div>
