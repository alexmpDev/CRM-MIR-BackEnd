<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\RoleServices;

class RoleController extends Controller
{
    public function __construct(
        protected RoleServices $roleService
    ){}
    public function index()
    {
        return $this->roleService->list();
    }

    public function store(UserRequest $request)
    {
        return $this->roleService->create($request->all());
    }

    public function show($id)
    {
       return $this->roleService->list_one($id);
    }

    public function update(UserRequest $request, $id)
    {
        return $this->roleService->update($request->all(), $id);
    }

    public function destroy($id)
    {
       return $this->roleService->delete($id);
    }

    public function update_workaround(UserRequest $request, $id)
    {

        return $this->update($request, $id);
    }
}
