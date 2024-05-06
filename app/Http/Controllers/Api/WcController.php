<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WcRequest;
use App\Services\WcService;

class WcController extends Controller
{

    public function __construct(
        protected  WcService $wcService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->wcService->list();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WcRequest $request)
    {
        return $this->wcService->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->wcService->listOne($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WcRequest $request, string $id)
    {
        return $this->wcService->edit($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->wcService->delete($id);
    }
}
