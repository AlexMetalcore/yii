<?php

namespace backend\models;

use common\models\LikePosts;
use common\models\User;
use yii\db\ActiveRecord;
use asinfotrack\yii2\comments\behaviors\CommentsBehavior;
use yii\web\UploadedFile;

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
    /**
     * @var string
     */
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

    /**
     * @return string
     */
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
    public function getAuthor()
    {
       return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLike()
    {
        return $this->hasMany(LikePosts::class, ['id' => 'post_id']);
    }

    /**
     * @return bool
     */
    public function ViwedCounter(Post $model) {
        $this->viewed += 1;
        if(!\Yii::$app->request->post()){
            $model->save(false);
        }
        \Yii::$app->session->removeAllFlashes();

    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getDate() {
        \Yii::$app->formatter->locale = 'ru-RU';
        return \Yii::$app->formatter->asDate($this->publish_date);
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public static function removeImgTags ($str) {
        return preg_replace('#<img[^>]*>#i', '', $str);
    }

    public function createFilePath (Post $model){
        $model->upload = UploadedFile::getInstance($model, 'upload');
        return $model->upload ? 'images/' . $model->upload->baseName . '.' . $model->upload->extension : $model->img;
    }
}
