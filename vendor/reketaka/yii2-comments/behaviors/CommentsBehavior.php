<?php

namespace reketaka\comments\behaviors;

use yii\base\Behavior;
use reketaka\comments\models\Comments;
use yii\base\Event;
use yii\db\ActiveRecord;

class CommentsBehavior extends Behavior{

    /**
     * Название атрибута в котором буде вестись количество оставленных комментариев
     * @var bool
     */
    public $attributeNameCountComments = false;

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_DELETE=>'onDelete'
        ];
    }


    public function onDelete(Event $event){
        $model = $event->sender;

        Comments::deleteAll(['class_name'=>$model::className(), 'identify_key'=>$model->id]);

        return true;

    }

    public function getComments(){

        $owner = $this->owner;

        $r = Comments::find()->all();

        return $r;

    }

    public function getCountComments(){

        /**
         * @var $owner ActiveRecord
         */
        $owner = $this->owner;

        if($this->attributeNameCountComments && $owner->hasAttribute($this->attributeNameCountComments)){
            return $owner->{$this->attributeNameCountComments};
        }

        $r = Comments::getDb()->cache(function()use($owner){
            return Comments::queryModel($this->owner)->count();
        });

        return $r;
    }

}