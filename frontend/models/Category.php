<?php

namespace frontend\models;

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
            'title' => 'Название',
            'count' => 'Количество записей',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['category_id' => 'id']);
    }


    public static function getQueryCategory () {

        return Category::find();

    }
}
