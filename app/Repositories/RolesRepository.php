<?php

namespace App\Repositories;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Repositories\interfaces\RolesRepositoryInterface;

class RolesRepository implements RolesRepositoryInterface {


    /**
     * get all data
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function all()
    {
        return RoleResource::collection(Role::query()->orderBy('created_at','DESC')->with('permissions')->get());
    }


    /**
     * @param array $data
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(array $data)
    {
        $role = \Spatie\Permission\Models\Role::create([
            'name'=>$data['name'],
            'guard_name'=>"web"
        ]);
        return response($role);

    }


    /**
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show($id)
    {
        return RoleResource::collection(Role::query()->orderBy('created_at','DESC')->where('id',$id)->with('permissions')->get());

    }


    /**
     * @param $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function update($request,$id)
    {

        try {
            $role = Role::query()->findOrFail($id);
            $role->update(['name'=>$request->name]);
            return response($role);

        }catch (\Exception $e){
            return response(['result' => 'not found , enter correct name and param id'], 404)->header('Content-Type', 'application/json');
        }
    }


    /**
     * @param array $data
     * @return RoleResource
     */
    public function sync_route_permissions_menu(array $data)
    {
        try {
            $role = \Spatie\Permission\Models\Role::where('id',$data['role_id'])->first();
            $role->syncPermissions($data['permissions']);

            return new RoleResource($role);
        }catch (\Exception $exception){
            return response(['result' => 'not found'], 404)->header('Content-Type', 'application/json');
        }

    }




    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            if($id != 1){
                $model =  Role::destroy($id);
                if($model){
                    return response()->json(['message'=>"Role deleted!"]);
                }else{
                    return response()->json(['message'=>"There is no record"]);
                }
            }else{
                return  response()->json('You cannot remove a super admin',400);
            }
        }catch (\Exception $exception){
            return response(['result' => 'not found'], 404)->header('Content-Type', 'application/json');
        }

    }

}
