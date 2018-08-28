<?php

namespace reketaka\comments\widgets;


use reketaka\comments\helpers\BaseHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use reketaka\comments\Module;
use reketaka\comments\models\Comments;
use yii\db\ActiveRecord;
use yii\helpers\StringHelper;

class CommentFormWidget extends Widget{

    /**
     * @var $model ActiveRecord
     */
    public $model;

    private $commentFormModel;

    /**
     * Путь до фаилы темы формы добавления комментария
     * @var
     */
    private $viewPath;
    /**
     * Id дочерней модели для которой оставляют комментарий
     * @var
     */
    private $primaryKeyName;
    private $primaryKeyValue;

    public function init()
    {
        /**
         * @var $module Module
         */
        $module = Yii::$app->getModule('comments');

        BaseHelper::checkModel($this->model);

        $this->commentFormModel = new Comments([
            'scenario'=>Yii::$app->user->isGuest?Comments::SCENARIO_FORM_GUEST:Comments::SCENARIO_FORM_USER
        ]);

        $this->viewPath = $module->commentFormWidget['viewPath'];
        $this->primaryKeyName = key($this->model->getPrimaryKey(true));
        $this->primaryKeyValue = $this->model->{$this->primaryKeyName};

    }

    public function run()
    {
        $module = Yii::$app->getModule('comments');

        if(Yii::$app->user->isGuest && !$module->guestCanComment) {
            return null;
        }

        return $this->render($this->viewPath, [
            'model'=>$this->commentFormModel,
            'item'=>$this->model,
            'id'=>$this->primaryKeyValue,
            'class_name'=>$this->model::className()
        ]);
    }

}