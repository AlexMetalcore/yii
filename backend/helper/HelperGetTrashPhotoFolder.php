<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 02.01.19
 * Time: 1:46
 */

namespace backend\helper;
use common\models\Portfolio;
use backend\models\Post;

/**
 * Class HelperClearTrashPhotoFolder
 * @package backend\helper
 */
class HelperGetTrashPhotoFolder
{
    /**
     * @var string
     */
    private $path;
    /**
     * @var array
     */
    public $array_photo;

    /**
     * HelperClearTrashPhotoFolder constructor.
     * @param Portfolio $portfolio
     * @param Post $post
     */
    public function __construct()
    {
        $this->path = \Yii::$app->basePath.'/web/images';
        $this->array_photo = $this->getTrashArrayPhoto();

    }

    /**
     * @param $portfolio
     * @param $posts
     * @return array
     */
    private function getTrashArrayPhoto ()
    {
        $imgportfolio = [];
        $imgpost = [];
        $onlyimg = [];
        $allimg = scandir($this->path);

        $portfolio = Portfolio::find()->all();
        $posts = Post::find()->all();

        foreach ($allimg as $img) {
            if (preg_match('/\.(jpg)|(jpeg)|(bmp)|(png)/', $img)) {
                $onlyimg[] = $img;
            }
        }

        foreach ($portfolio as $img) {
            $imgs = explode(',' , $img->img);
            foreach ($imgs as $imgitem){
                $imgportfolio[] = basename($imgitem);
            }
        }
        foreach ($posts as $post_img) {
            $imgpost[] = basename($post_img->img);
        }

        $global_array_img = array_merge($imgportfolio , $imgpost);
        $delete_img = array_diff($onlyimg , $global_array_img);

        return $delete_img;
    }
}