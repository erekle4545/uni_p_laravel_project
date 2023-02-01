<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EndpointController;
use App\Http\Controllers\API\RoleController;
use App\Models\Permission;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * global api requests
 */
Route::group(['prefix' => 'v1'],function () {
    // user register & login
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register',[AuthController::class,'register']);

    // clear all cache & config
    Route::get('/clear', function() {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        return response()->json(['message'=>"config and cache cleared!"]);
    });
});

/**
 * auth api Requests
 */
Route::group(['prefix' => 'v1','middleware'=>'auth:sanctum'],function () {
    /**
        * global Requests all authorized users
     */
        Route::post('/logout', [AuthController::class, 'logout']);
    /**
     * Only super admin requests
     */
    Route::group(['middleware' => ['role:super_admin']], function () {
        // get all users
        Route::get('/users',[AuthController::class,'getUser']);
        // roles
        Route::resource('/role',RoleController::class);
        Route::put('/update/role/{id}',[RoleController::class,'update']);
        Route::delete('/delete/role/{id}',[RoleController::class,'destroy']);
        // assign role to user
        Route::post('/assign/role/user',[AuthController::class,'assigningRoleToUser']);
        // permissions
        Route::resource('/permissions',\App\Http\Controllers\API\PermissionController::class);
        Route::put('/update/permissions/{id}',[\App\Http\Controllers\API\PermissionController::class,'update']);
        Route::delete('/delete/permissions/{id}',[\App\Http\Controllers\API\PermissionController::class,'deletePermission']);
        // role and permission
        Route::put('/sync_route_permissions',[RoleController::class,'sync_route_permissions_menu']);
        // user
        Route::delete('/users/delete/{id}',[AuthController::class,'deleteUser']);
    });
    /**
     *  Generate permissions routes & endpoint
     */
        $templates = array_values(config('menu.templates'));
        if(Schema::hasTable('permissions')){
            foreach (Permission::all() as $permissionRow){
                $generatePermission = explode(' ',$permissionRow->name);
                foreach ($templates as $endPoint){
                    Route::middleware(["permission:{$generatePermission[0]} {$endPoint['key']}"])->get($endPoint['key'],[EndpointController::class,'index']);
                }
            }
        }
});


