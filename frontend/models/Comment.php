<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property string $comment
 * @property string $model_type
 * @property int $model_id
 * @property int $state_id
 * @property int $type_id
 * @property string $create_time
 * @property int $create_user_id
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment'], 'string'],
            [['model_id', 'state_id', 'type_id', 'create_user_id'], 'integer'],
            [['create_time'], 'safe'],
            [['model_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'comment' => 'Comment',
            'model_type' => 'Model Type',
            'model_id' => 'Model ID',
            'state_id' => 'State ID',
            'type_id' => 'Type ID',
            'create_time' => 'Create Time',
            'create_user_id' => 'Create User ID',
        ];
    }
}
