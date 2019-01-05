<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\models\Post;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статьи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="create-post">
        <?= Html::a('Создать запись', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?php Pjax::begin([
        'id' => 'pjax-list',
    ]); ?>
    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout'=>"{summary}\n{items}\n{pager}",
            'summary' => 'Показано {count} из {totalCount} статтей',
            'emptyText' => 'Статтей нету',
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
                            return Html::a('', false , ['class' => 'glyphicon glyphicon-trash delete-post-item' , 'pjax-container-post' => 'pjax-list', 'delete-url-post'  => $url, 'title' => 'Delete']);

                        },
                    ],
                ],
            ],
    ]); ?>
    <?php
    $this->registerJs("
        $('.delete-post-item').click(function(e) {
            $('.alert-success').children().children().trigger('click');
            e.preventDefault();
            var url = $(this).attr('delete-url-post');
            var id = url.split('=');
            var pjaxContainer = $(this).attr('pjax-container-post');
            $.ajax({
                url: '/admin/post/delete',
                data: { id: id[1] },
                type: 'GET',
                success: function (res) {
                    $('.breadcrumb').after('<div class=\"alert alert-success alert-dismissible\" role=\"alert\">'+res+'<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button></div>');
                },
                error: function () {
                    alert('Ошибка!');
                }
            }).done(function () {
                $.pjax.reload({container: '#' + $.trim(pjaxContainer)});
            });
});
    ");?>
    <?php Pjax::end(); ?>
</div>
