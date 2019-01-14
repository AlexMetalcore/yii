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
    <?php Pjax::begin([
        'id' => 'pjax-list',
    ]); ?>

    <?php if (Yii::$app->session->hasFlash('delete')): ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php echo Yii::$app->session->getFlash('delete'); ?>
        </div>
    <?php endif;?>

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="create-post">
        <?= Html::a('Создать запись', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
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
                    'attribute' => 'content',
                        'value'  => function($model){
                            return strlen(Post::removeImgTags($model->content)) < 200 ? $model->content : mb_substr($model->content , '0' , 200). '...';
                        },
                        'format' => 'raw'
                ],
                [
                    'attribute' => 'category_id',
                        'value' => function($model){
                            return $model->category->title ? $model->category->title : 'Категория отсутствует';
                        },
                ],
                [
                    'attribute' => 'author_id',
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
                        return $model->viewed ? $model->viewed : 'Статью никто еще не просматривал';
                    }
                ],
                [
                        'attribute' => 'publish_date',
                        'value' => function($model) {
                            return $model->getDate();
                        }
                ],
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
            var id = url.split('=')[1];
            var pjaxContainer = $(this).attr('pjax-container-post');
            $.ajax({
                url: '/admin/post/delete',
                data: { id: id },
                type: 'GET',
                success: function (res) {
                    $('.breadcrumb').after(res);
                },
                error: function (res) {
                    alert(res.responseText);
                }
            }).done(function () { $.pjax.reload({container: '#' + $.trim(pjaxContainer)});});
        });
    ");?>
    <?php Pjax::end(); ?>
</div>