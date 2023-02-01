<?php

namespace App\Repositories\interfaces;

interface AuthRepositoryInterface{

    public function register(array $data);

    public function login(array $credentials);

    public function getUser ();

    public function assigningRoleToUser(array $data);
    public function logout();

    public function deleteUser($id);


}
