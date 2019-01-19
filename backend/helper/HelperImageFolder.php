<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 02.01.19
 * Time: 1:46
 */

namespace backend\helper;

use backend\models\Portfolio;
use backend\models\Post;
use backend\models\Settings;
use backend\models\User;

/**
 * Class HelperImageFolder
 * @package backend\helper
 */
class HelperImageFolder
{
    /**
     * @var string
     */
    private $path_static;
    /**
     * @var array
     */
    public $array_image;
    /**
     * @var string
     */
    public $path;

    /**
     * HelperImageFolder constructor.
     * @param Portfolio $portfolio
     * @param Post $post
     */
    public function __construct()
    {
        $this->path = \Yii::$app->basePath.'/web/images/';
        /*transfer variable to view in any controller*/
        $this->array_image = $this->getTrashArrayPhoto();
        $this->path_static = \Yii::$app->basePath.'/web/images/staticimg/';

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
        $imguser = [];
        $imgpostthumb = [];
        $onlyimg = [];
        $allimg = scandir($this->path);

        $portfolio = Portfolio::find()->all();
        $posts = Post::find()->all();
        $users = User::find()->all();

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
            $imgpostthumb[] = basename($post_img->thumb_img);
            $allimginpost = array_merge($imgpost , $imgpostthumb);
        }

        foreach ($users as $user) {
            $imguser[] = basename($user->user_img);
        }

        $global_array_img = array_merge($imgportfolio , $allimginpost , $imguser);
        $delete_img = array_diff($onlyimg , $global_array_img);

        return $delete_img;
    }

    /**
     * @return string
     */
    public function deleteTrashImg () {

        $delete_img = $this->getTrashArrayPhoto();

        if($delete_img) {
            foreach ($delete_img as $img) {
                $file_delete = $this->path.$img;
                if (file_exists($file_delete)) {
                    unlink($file_delete);
                    \Yii::$app->session->setFlash('success' , 'Старые картинки удалены');
                }
            }
        }
    }


    /**
     * @return array
     */
    public function staticFolderImage ()
    {
        $get_all_img = scandir($this->path_static);
        $onlyimg = [];
        foreach ($get_all_img as $img) {
            if (preg_match('/\.(jpg)|(jpeg)|(bmp)|(png)/', $img) && filesize($this->path_static.$img) > Settings::get(Settings::FILESIZE_FILE_COMPRESSION)) {
                $onlyimg[] = $this->path_static.$img;
            }
        }
        return $onlyimg;
    }

    public function getAllImages ()
    {
        $dir_images = scandir($this->path);
        $dir_static_img = scandir($this->path_static);
        foreach ($dir_images as $img) {
            if (preg_match('/\.(jpg)|(jpeg)|(bmp)|(png)/', $img)) {
                $img_src[] = '/admin/images/'.$img;
            }
        }
        foreach ($dir_static_img as $img) {
            if (preg_match('/\.(jpg)|(jpeg)|(bmp)|(png)/', $img)) {
                $img_src_static[] = '/admin/images/staticimg/' . $img;
            }
        }

        return array_merge($img_src , $img_src_static);
    }
}