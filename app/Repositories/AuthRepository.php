<?php

namespace App\Repositories;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Repositories\interfaces\AuthRepositoryInterface;
class AuthRepository implements AuthRepositoryInterface{

    /**
     * @param array $data
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function register(array $data)
    {
        /** @var \App\Models\User $user */
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);
        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);

    }


    /**
     * @param array $credentials
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function login(array $credentials)
    {
        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);

        if (!Auth::attempt($credentials,$remember)) {
            return response([
                'error' => 'The Provided credentials are not correct'
            ], 422);
        }

        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;
        $userData = User::where('id',$user->id)->with('roles.permissions')->first();

        return response([
            'user' => $userData,
            'token' => $token
        ]);
    }

    /**
     * @return UserResource
     */
    public function getUser (){
        $userData = User::query()->with(['roles.permissions'])->get();
        return new UserResource($userData);
    }


    /**
     * @param array $data
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function assigningRoleToUser(array $data){
        try {
            $user = User::where('id',$data['user_id'])->first();
            $user->each(function ($user) use ($data){
                $user->assignRole($data['role_id']);
            });

            return  response($user,200);
        }catch (\Exception $exception){
            return response(['result' => 'not found object',"message"=>$exception->getMessage()], 404)->header('Content-Type', 'application/json');
        }

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function logout()
    {
        try {
            /** @var User $user */

            $user = Auth::user();
            // Revoke the token that was used to authenticate the current request...

            $user->currentAccessToken()->delete();

            return response()->json([
                'message' => "success request"
            ]);
        }catch (\Exception $exception){
            return response(['result' => 'internal server Error'], 500)->header('Content-Type', 'application/json');

        }

    }

    /**
     * @param $id
     * @return void
     */
    public function deleteUser($id){
        if($id !== 1){

            $userDestroy = User::destroy($id);

            return response($userDestroy);

        }else{

            return  response()->json('You cannot remove a super admin',400);

        }
    }
}
