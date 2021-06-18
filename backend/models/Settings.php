<?php

namespace backend\models;

/**
 * This is the model class for table "settings".
 *
 * @property string $key
 * @property string $name
 * @property string $value
 * @property string $default_value
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * @var integer
     */
    const TIME_CACHE_WIDGET = 'time_cache_widget';

    /**
     * @var integer
     */
    const PARAMETERS_QUALITY = 'parameters_quality';
    /**
     * @var integer
     */
    const PARAMETERS_COMPRESSION = 'parameters_compression';

    /**
     * @var integer
     */
    const WIDTH_IMAGE_RESIZE = 'width_image_resize';
    /**
     * @var integer
     */
    const HEIGHT_IMAGE_RESIZE = 'height_image_resize';
    /**
     * @var integer
     */
    const COUNT_LAST_POST = 'count_last_post';
    /**
     * @var integer
     */
    const COUNT_POPULAR_POST = 'count_popular_post';

    /**
     * @var integer
     */
    const FILESIZE_FILE_COMPRESSION = 'filesize_file_compression';

    /**
     * @var integer
     */
    const COUNT_LAST_USER_REGISTERED = 'count_last_user_registered';

    /**
     * @var integer
     */
    const COUNT_LAST_PORTFOLIO = 'count_last_potrfolio';

    /**
     * @var integer
     */
    const MAX_ITEM_IN_BLOG = 'max_item_in_blog';

    /**
     * @var string
     */
    const NAME_BLOG_TITLE = 'name_blog_title';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key', 'name', 'value'], 'required'],
            [['value', 'default_value'], 'string'],
            [['key'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 250],
            [['key'], 'unique', 'targetClass' => '\backend\models\Settings', 'message' => 'Настройка существует'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'key' => 'Ключ доступа',
            'name' => 'Название настройки',
            'value' => 'Значение',
            'default_value' => 'Значение по умолчанию',
        ];
    }

    /**
     * @param $key
     * @param bool $default
     * @return bool
     */
    public static function get($key, bool $default = false)
    {
        $row = static::findOne(['key' => $key]);

        if ($row === false) {
            $result = (strlen($row['value']) ? $row['value'] : $row['default_value']);
            return $result;
        }

        return $default;
    }
}
