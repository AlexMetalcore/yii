<?php

//namespace frontend\models;

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="user-index">

    <?php Pjax::begin([
        'id' => 'pjax-list',
    ]); ?>

    <?php if (Yii::$app->session->hasFlash('delete_user')): ?>
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php echo Yii::$app->session->getFlash('delete_user'); ?>
        </div>
    <?php endif;?>

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
            [
                'attribute' => 'username',
                'value'  => function ($model) {
                    return $model->username;
                }
            ],
            'email:email',
            'about:ntext',
            [
                    'attribute' => 'status',
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
            [
                'attribute' => 'count_post',
                'value'  => function ($model) {
                    return $model && count($model->post) > 0 ? count($model->post) : 'У автора нету статтей';
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
                'header' => 'Действия',
                'buttons' => [
                    'delete' => function ($url , $model) {
                        return count($model->post) > 0 ? Html::a('', false, ['class' => 'glyphicon glyphicon-trash delete-user-item-and-post' , 'pjax-container' => 'pjax-list', 'delete-url'  => $url, 'title' => 'Delete' , 'data-user' => $model->username]) : Html::a('', false, ['class' => 'glyphicon glyphicon-trash delete-user-item' , 'pjax-container' => 'pjax-list', 'delete-url'  => $url, 'title' => 'Delete' , 'data-user' => $model->username ]);

                    },
                ],
            ],
        ],
    ]); ?>
    <?php
    Modal::begin([
        'header'=>'<h4>Удаление пользователя <span class="user-delete"></span></h4>',
        'id'=>'modal-delete-users-post',
        'size'=>'modal-md',
    ]);?>
    <div class="message-user"><span class="text">У пользователя есть статьи. Можно удалить все его статьи или перенести администратору</span><?= Html::img('/admin/images/staticimg/AjaxLoader2.gif' , ['class' => 'user-loader']) ?></div>
    <?=Html::button('Перенести статьи' , ['class' => 'btn btn-success' , 'id' => 'move-to-admin']);?>
    <?=Html::button('Удалить все статьи' , ['class' => 'btn btn-danger pull-right' , 'id' => 'delete-all-post']);?>
    <?php
        Modal::end();
    ?>
    <?php
    $this->registerJs("
        function getResponseAfterDelete (res) {
            var pjaxContainer = $('.delete-user-item-and-post').attr('pjax-container');
            text.hide();
            loader.fadeIn();
            $('#move-to-admin , #delete-all-post').prop('disabled', true);
            setTimeout(function () {
                message.replaceWith(res);
                    $('#move-to-admin , #delete-all-post').hide();  
                },3000);
            setTimeout(function () {
                $('#modal-delete-users-post').modal('hide');
            },5000);
            setTimeout(function () {
                    $.pjax.reload({
                       container: '#' + $.trim(pjaxContainer)
                    });
            },5200)       
        }
        $('.delete-user-item-and-post').on('click' , function(e) {
            $('#modal-delete-users-post').modal('show');
            loader = $('.user-loader');
            text = $('.text');
            message = $('.message-user');
            var url = $(this).attr('delete-url');
            var id = url.split('=')[1];
            var user = $(this).attr('data-user');
            $('.user-delete').html(user);
            $('#move-to-admin').on('click' , function(e) {
                $.ajax({
                    url: '/admin/user/delete',
                    data: { id: id },
                    type: 'GET',
                    success: function (res) {
                         getResponseAfterDelete(res);       
                    },
                    error: function (res) {
                        alert(res);
                    }
                });
            });
            
            $('#delete-all-post').on('click' , function(e) {
                $.ajax({
                    url: '/admin/user/delete',
                    data: { id: id , move: false},
                    type: 'GET',
                    success: function (res) {
                        getResponseAfterDelete(res);       
                    },
                    error: function (res) {
                        alert(res);
                    }
                });
            });
        });
        
        $('.delete-user-item').click(function(e) {
            $('.alert-success').children().children().trigger('click');
            e.preventDefault();
            if (confirm('Вы точно хотите удалять пользователя?')) {
                var url = $(this).attr('delete-url');
                var id = url.split('=')[1];
                var user = $(this).attr('data-user');
                var pjaxContainer = $(this).attr('pjax-container');
                
                $.ajax({
                    url: '/admin/user/delete',
                    data: { id: id , user: user },
                    type: 'GET',
                    success: function (res) {
                        $('.breadcrumb').after(res);
                    },
                    error: function (res) {
                        alert(res);
                    }
                }).done(function (data) { $.pjax.reload({container: '#' + $.trim(pjaxContainer)});});
            }
        });
    ");?>
    <?php Pjax::end(); ?>
</div>
