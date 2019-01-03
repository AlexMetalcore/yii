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
     * @var int
     */
    public $count;
    /**
     * @var string
     */
    private $path;

    /**
     * HelperClearTrashPhotoFolder constructor.
     * @param Portfolio $portfolio
     * @param Post $post
     */
    public function __construct()
    {
        $this->path = \Yii::$app->basePath.'/web/images';
        /*transfer variable to view in any controller*/
        $this->count = count($this->getTrashArrayPhoto());

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

    /**
     * @return string
     */
    public function deleteTrashImg () {

        $delete_img = $this->getTrashArrayPhoto();

        $what_files = [];
        if($delete_img) {
            foreach ($delete_img as $img) {
                $file_delete = \Yii::$app->basePath.'/web/images/'.$img;
                $what_files[] = $file_delete;
                /*transfer variable to view in any controller*/
                $files_delete = implode('<br>' , $what_files);
                if (file_exists($file_delete)) {
                    unlink($file_delete);
                    \Yii::$app->session->setFlash('success' , 'Старые картинки удалены');
                }
            }
            return $files_delete;
        } else {
            \Yii::$app->session->setFlash('warning' , 'Нету картинок для удаления');
        }

    }
}