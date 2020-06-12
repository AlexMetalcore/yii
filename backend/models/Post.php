<?php

namespace backend\models;

use yii\db\ActiveRecord;
use asinfotrack\yii2\comments\behaviors\CommentsBehavior;
use \yii\web\UploadedFile;
use common\implement\UploadFileInterfaces;

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
class Post extends ActiveRecord implements UploadFileInterfaces
{
    /**
     * @var string
     */
    public $upload;
    /**
     * @var string
     */
    private $imgthumb;


    /**
     * @param $imgthumb
     * @param int $width
     * @param int $height
     * @return string
     * @throws \ImagickException
     */
    public function setImgThumb($imgthumb , $width = 300 , $height = 200 )
    {
        $this->imgthumb = $this->createImgThumb($imgthumb , $width = 300 , $height = 200);
        return $this->imgthumb;
    }
    /**
     * @param $filePath
     * @return string
     * @throws \ImagickException
     */
    private function createImgThumb($filePath , $width = 300 , $height = 200)
    {
        $thumb = new \Imagick($filePath);
        $thumb->resizeImage($width , $height, \Imagick::FILTER_LANCZOS,1);
        $thumb_path = 'images/'.uniqid().basename($thumb->getImageFilename());
        $thumb->writeImage($thumb_path);
        chmod(\Yii::$app->basePath.'/web/'.$thumb_path, 0777);
        return $thumb_path;
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'comments'=>[
                'class' => CommentsBehavior::className(),
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
            //[['publish_date'], 'safe'],
            //[['publish_date'], 'date', 'format'=> date('yyyy-mm-dd')],
            [['publish_date'], 'default', 'value' => 'yyyy-mm-dd'],
            ['title', 'unique', 'targetClass' => '\backend\models\Post', 'message' => 'Запись существует'],
            [['title'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['upload'], 'file', 'extensions' => 'png, jpg , jpeg , bmp'],
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
        return $this->hasMany(LikePosts::class, ['post_id' => 'id']);
    }

    /**
     * @return bool
     */
    public function ViwedCounter($id)
    {
        $this->viewed += 1;
        $this->save(false);
        \Yii::$app->session->removeAllFlashes();

    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getDate()
    {
        \Yii::$app->formatter->locale = 'ru-RU';
        return \Yii::$app->formatter->asDate($this->publish_date);
    }

    /**
     * @param $str
     * @return string|string[]|null
     */
    public static function removeImgTags ($str)
    {
        return preg_replace('#<img[^>]*>#i', '', $str);
    }

    /**
     * @return mixed|string
     */
    public function createFilePath()
    {
        $this->upload = UploadedFile::getInstance($this, 'upload');
        return $this->upload ? 'images/' . uniqid() . '.' . $this->upload->extension : $this->img;
    }

    /**
     * @return array last post
     */
    public static function getLastPost()
    {
        return self::find()->orderBy('id desc')->limit(Settings::get(Settings::COUNT_LAST_POST))->all();
    }

    /**
     * @return mixed
     */
    public static function getPopularPosts()
    {
        return self::find()->orderBy('viewed desc')->limit(Settings::get(Settings::COUNT_POPULAR_POST))->all();
    }

}
