<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRole;
use App\Http\Requests\StoreRoleUpdate;
use App\Repositories\interfaces\RolesRepositoryInterface;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $rolesRepository;

    /**
     * @param RolesRepositoryInterface $rolesRepository
     */
    public function __construct(RolesRepositoryInterface $rolesRepository)
    {
        $this->rolesRepository = $rolesRepository;
    }

    /** get all data
     * @return mixed
     */
    public function index()
    {
        return $this->rolesRepository->all();
    }


    /**
     * @param StoreRole $request
     * @return mixed
     */

    public function store(StoreRole $request)
    {
        $data = $request->validated();
        return $this->rolesRepository->store($data);
    }

    /**
     * @param $id
     * @return mixed
     */

    public function show($id)
    {
        return $this->rolesRepository->show($id);
    }

    /**
     * @param StoreRoleUpdate $request
     * @param $id
     * @return mixed
     */

    public function update(StoreRoleUpdate $request,$id)
    {
        return $this->rolesRepository->update($request,$id);

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function sync_route_permissions_menu(Request $request)
    {
        $data = $request->validate([
           'permissions'=>'array|required',
           'role_id'=>'required'
        ]);

        return $this->rolesRepository->sync_route_permissions_menu($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {

        return $this->rolesRepository->delete($id);

    }


}
