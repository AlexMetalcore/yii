<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.12.18
 * Time: 20:39
 */

namespace common\models;


use yii\db\ActiveRecord;

class Portfolio extends ActiveRecord {

    public static function tableName()
    {
        return parent::tableName();
    }
    public function rules()
    {
        return [
            [['content' , 'img'] , 'required'],
            [['id'] , 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => 'Контент',
            'img' => 'Картинка'
        ];
    }
}