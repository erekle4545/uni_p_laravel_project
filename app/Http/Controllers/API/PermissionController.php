<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissions;
use App\Http\Requests\StoreUpdatePermission;
use App\Http\Resources\PermissionsResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function __construct()
    {
        // cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * get permissions
     **/
    public function index()
    {
        return PermissionsResource::collection(Permission::query()->orderBy('created_at','DESC')->get());
    }

    /**
     * create permission
     * @param StorePermissions $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePermissions $request){
        $permission = Permission::create([
            'name'=>"{$request->name} {$request->endpoint}",
            'group'=>'',
            'guard_name'=>'web',
        ]);

        return response()->json($permission);

    }

    /**
     * update permission
     * @param StorePermissions $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update(StorePermissions $request,$id){
        try {
            $permissionData = Permission::query()->findOrFail($id);
            $permissionData->name = $request->name." ".$request->endpoint;
            $permissionData->save();
            return response()->json($permissionData);
        }catch (\Exception $exception){
            return response(['result' => 'not found'], 404)->header('Content-Type', 'application/json');
        }
    }

    /**
     * permission delete || array or one
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function deletePermission($id){
        try {

           $delRes =  Permission::destroy($id);

            if($delRes){
                return response()->json(['message'=>"Permission deleted"]);
            }else{
                return response()->json(['message'=>"There is no record"]);
            }

        }catch (\Exception $exception){
            return response(['result' => 'not found'], 404)->header('Content-Type', 'application/json');
        }
    }
}
