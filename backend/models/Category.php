<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $title
 *
 * @property Post[] $posts
 */
class Category extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            ['title', 'unique', 'targetClass' => '\backend\models\Category', 'message' => 'Категория существует'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'title' => 'Категория',
            'count' => 'Количество записей',
        ];
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            \Yii::$app->session->setFlash('success', 'Запись добавлена');
        } else {
            \Yii::$app->session->setFlash('success', 'Запись обновлена');
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['category_id' => 'id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getQueryCategory ()
    {

        return Category::find();

    }

    /**
     * @return array|ActiveRecord[]
     */
    public static function getAllCategory ()
    {

        return Category::find()->all();

    }
}
