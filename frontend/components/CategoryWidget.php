<?php
namespace frontend\components;

use backend\models\Category;
use yii\base\Widget;

/**
 * Class CategoryWidget
 * @package frontend\components
 */
class CategoryWidget extends Widget {

    /**
     * @var array
     */
    public $data_category = [];

    /**
     *
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @return string
     */
    public function run() {

        $categories = Category::find()->all();
        foreach ($categories as $category){
            $this->data_category[] = $category;
        }
        $data_category = $this->data_category;
        return $this->render('block-category' , compact('data_category'));
    }

}