<?php


namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\interfaces\AuthInterface;
use PharIo\Version\Exception;

class AuthController extends Controller implements AuthInterface
{
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
     * @param Request $request
     * Login post request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
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
     * get current user
     */

    public function getUser (){
        $userData = User::where('id',Auth::user()->id)->with(['roles.permissions'])->first();
        return new UserResource($userData);
    }




    /**
     * logout user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
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
