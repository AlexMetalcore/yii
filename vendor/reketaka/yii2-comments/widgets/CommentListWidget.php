<?php

namespace reketaka\comments\widgets;

use reketaka\comments\helpers\BaseHelper;
use reketaka\comments\models\Comments;
use Yii;
use yii\base\Widget;

class CommentListWidget extends Widget{

    /**
     * Путь до списка комментариев
     * @var
     */
    private $viewPath;

    /**
     * @var $model ActiveRecord
     */
    public $model;
    private $commentFormModel;

    public function init(){
        $module = Yii::$app->getModule('comments');

        BaseHelper::checkModel($this->model);

        $this->commentFormModel = Comments::findsByModel($this->model);

        $this->viewPath = $module->commentListWidget['viewPath'];
    }

    public function run(){
        return $this->render($this->viewPath, [
            'comments'=>$this->commentFormModel,
            'item'=>$this->model
        ]);
    }

}

?>