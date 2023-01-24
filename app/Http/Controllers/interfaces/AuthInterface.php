<?php

namespace App\Http\Controllers\interfaces;
use Illuminate\Http\Request;

interface AuthInterface{

    public function register(Request $request);

    public function login(Request $request);

    public function getUser ();

    public function logout();

    public function deleteUser($id);


}
