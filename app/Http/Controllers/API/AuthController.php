<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\interfaces\AuthRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    protected $AuthRepository;

    /**
     * implementation AuthRepository
     * @param AuthRepositoryInterface $AuthRepository
     */
    public function __construct(AuthRepositoryInterface $AuthRepository)
    {
        $this->AuthRepository = $AuthRepository;
    }

    /**
     * user Register with role
     * @param Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|string|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()
            ]
        ]);

        return $this->AuthRepository->register($data);
    }


    /**
     * user login
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => 'required|email|string|exists:users,email',
            'password' => [
                'required',
            ],
            'remember' => 'boolean'
        ]);

        return $this->AuthRepository->login($credentials);
    }


    /**
     * get user data
     * @return mixed
     */
    public function getUser (){
          return $this->AuthRepository->getUser();
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function assigningRoleToUser(Request $request){

        $data = $request->validate([
            'user_id' => 'required',
            'role_id' => 'required'
        ]);

        return $this->AuthRepository->assigningRoleToUser($data);
    }

    /**
     * logout user
     * @return mixed
     */
    public function logout()
    {
        return $this->AuthRepository->logout();

    }

    /**
     * delete users
     * @param $id
     * @return mixed
     */
    public function deleteUser($id){
        return $this->AuthRepository->deleteUser($id);
    }

}
