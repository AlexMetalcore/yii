<?php


namespace common\repositories;

use \backend\models\User;

class UserRepository implements UserRepositoryInterface
{

    public function get($id)
    {
       var_dump($id);die;
    }

    public function create(User $user)
    {
        // TODO: Implement create() method.
    }

    public function save(User $user)
    {
        // TODO: Implement save() method.
    }

    public function remove(User $user)
    {
        // TODO: Implement remove() method.
    }
}