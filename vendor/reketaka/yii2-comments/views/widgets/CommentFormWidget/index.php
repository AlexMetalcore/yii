<?php

use reketaka\comments\Module;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use reketaka\comments\models\Comments;
use yii\helpers\StringHelper;
/**
 * @var $model \reketaka\comments\models\Comments
 * @var $item
 * @var $id
 * @var $class_name
 */

if(is_array($errors = Yii::$app->session->getFlash(Comments::$errorKeyName))){
    $model->addErrors($errors);
}

?>

<div class="post-comment-form card card--lg">
    <header class="post-comment-form__header card__header">
        <h4><?=Module::t('base', 'write_your_comment')?></h4>
    </header>
    <div class="post-comment-form__content card__content">
            <?php $form = ActiveForm::begin([
                    'method'=>'post',
                    'action'=>Url::to(['comments/index/send']),
                    'options'=>[
                            'class'=>['comment-form']
                    ]
            ])?>

            <div class="hide">
                <?=$form->field($model, 'class_name')->hiddenInput(['value'=>$class_name])->label(false)?>

                <?=$form->field($model, 'identify_key')->hiddenInput(['value'=>$id])->label(false)?>
            </div>

            <?php if($model->scenario == $model::SCENARIO_FORM_GUEST):?>

                <div class="row">
                    <div class="col-md-6">
                        <?=$form->field($model, 'name')?>
                    </div>
                    <div class="col-md-6">
                        <?=$form->field($model, 'email')?>
                    </div>
                </div>

            <?php endif; ?>

            <?=$form->field($model, 'content')->textarea(['rows'=>7])?>

            <div class="form-group">
                <button type="submit" class="btn btn-default btn-block btn-lg"><?=Module::t('base', 'send_comment')?></button>
            </div>
        <?php ActiveForm::end() ?>
    </div>
</div>