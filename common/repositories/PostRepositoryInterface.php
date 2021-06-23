<?php

namespace common\repositories;

use backend\models\Post;

interface PostRepositoryInterface
{
    public function get(int $id);

    public function getAll();

    public function create(Post $post);

    public function save(Post $user);

    public function remove(Post $user);
}