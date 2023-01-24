<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRole;
use App\Http\Requests\StoreRoleUpdate;
use App\Http\Resources\PermissionsResource;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return RoleResource::collection(Role::query()->orderBy('created_at','DESC')->with('permissions')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRole $storeRole)
    {
        $data = $storeRole->validated();
        $role = \Spatie\Permission\Models\Role::create([
            'name'=>$data['name'],
            'guard_name'=>"web"
        ]);
        return response($role);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return RoleResource::collection(Role::query()->orderBy('created_at','DESC')->where('id',$id)->with('permissions')->get());

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @param StoreRoleUpdate $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function update(StoreRoleUpdate $request,$id)
    {

        try {
            $role = Role::query()->findOrFail($id);
            $role->update(['name'=>$request->name]);
            return response($role);

        }catch (\Exception $e){
            return response(['result' => 'not found'], 404)->header('Content-Type', 'application/json');
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function sync_route_permissions_menu(Request $request)
    {
        $data = $request->validate([
           'permissions'=>'array',
           'role_id'=>'required'
        ]);

        $role = \Spatie\Permission\Models\Role::where('id',$data['role_id'])->first();
        $role->syncPermissions($data['permissions']);

        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
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
