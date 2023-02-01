<?php

namespace App\Repositories\interfaces;

interface PermissionsRepositoryInterface{

    public function all();

    public function store( $request);

    public function update($request,$id);

    public function deletePermission($id);
}
