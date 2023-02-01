<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePermissions;
use App\Repositories\interfaces\PermissionsRepositoryInterface;


class PermissionController extends Controller
{
    protected $permissionsRepository;


    public function __construct(PermissionsRepositoryInterface $permissionsRepository)
    {
        $this->permissionsRepository = $permissionsRepository;
        // cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * get permissions
     **/
    public function index()
    {
        return $this->permissionsRepository->all();
    }

    /**
     * @param StorePermissions $request
     * @return mixed
     */
    public function store(StorePermissions $request){
        return   $this->permissionsRepository->store($request);
    }


    /**
     * @param StorePermissions $request
     * @param $id
     * @return mixed
     */
    public function update(StorePermissions $request,$id){
        return   $this->permissionsRepository->update($request,$id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deletePermission($id){
        return   $this->permissionsRepository->deletePermission($id);

    }
}
