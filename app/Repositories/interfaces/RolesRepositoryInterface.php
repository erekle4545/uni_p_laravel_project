<?php

namespace App\Repositories\interfaces;

interface RolesRepositoryInterface{

    public function all();

    public function show($id);

    public function store(array $data);

    public function update( $request,$id);

    public function sync_route_permissions_menu(array $data);

    public function delete($id);

}
