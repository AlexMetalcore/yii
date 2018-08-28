<?php

namespace backend\models;

use common\models\User;

use yii\db\ActiveRecord;
use asinfotrack\yii2\comments\behaviors\CommentsBehavior;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $title
 * @property string $anons
 * @property string $content
 * @property int $category_id
 * @property int $author_id
 * @property string $publish_status
 * @property string $publish_date
 *
 * @property User $author
 * @property Category $category
 */
class Post extends ActiveRecord
{
    public $upload;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'comments'=>[
                'class'=>CommentsBehavior::className(),
            ],
        ];
    }
    public static function tableName()
    {
        return 'post';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['anons', 'content', 'publish_status'], 'string'],
            [['category_id', 'author_id'], 'integer'],
            [['publish_date'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['upload'], 'file', 'extensions' => 'png, jpg , jpeg , jpg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Заголовок',
            'content' => 'Описание',
            'img'   => 'Фото',
            'category_id' => 'Категория',
            'author_id' => 'Автор',
            'publish_status' => 'Статус статьи',
            'publish_date' => 'Дата публикации',
            'upload' => 'Загрузить фото'
        ];
    }

    public function getAuthor()
    {
       return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

}
