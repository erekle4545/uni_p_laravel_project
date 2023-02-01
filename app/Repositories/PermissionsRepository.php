<?php

namespace App\Repositories;

use App\Http\Resources\PermissionsResource;
use App\Repositories\interfaces\PermissionsRepositoryInterface;
use Spatie\Permission\Models\Permission;

class PermissionsRepository implements PermissionsRepositoryInterface {

    /**
     * get all data
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function all()
    {
        return PermissionsResource::collection(Permission::query()->orderBy('created_at','DESC')->get());
    }


    /**
     * @param  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store( $request){
        $permission = Permission::create([
            'name'=>"{$request->name} {$request->endpoint}",
            'group'=>'',
            'guard_name'=>'web',
        ]);

        return response()->json($permission);
    }

    /**
     * @param $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function update($request,$id){
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
