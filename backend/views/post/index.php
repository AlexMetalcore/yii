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

    <p class="create-post">
        <?= Html::a('Создать запись', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <p class="delete-all-img">
        <?= Html::button('Удалить старые картинки'.Html::img('/admin/images/staticimg/loaderbtn.gif' , ['class' => 'loader-delete']).'', ['class' => 'btn btn-danger' , 'id' => 'btn-delete-img']); ?>
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
                [
                    'header' => 'Количество просмотров',
                    'value'  => function($model){
                        return $model->viewed;
                    }
                ],
                'publish_date',
                ['class' => 'yii\grid\ActionColumn',
                    'header' => 'Действия',
                    'buttons' => [
                        'delete' => function ($url) {
                            return Html::a('', ['..'.$url] , ['class' => 'glyphicon glyphicon-trash' , 'title' => 'Delete' , 'onClick' => 'return confirm("Вы хотите удалить данную запись?")']);

                        },
                    ],
                ],
            ],
    ]); ?>
</div>
