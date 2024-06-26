<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\UserServices;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserServices $userService
    ){}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
       return $this->userService->list();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(UserRequest $request)
    {

        return $this->userService->create($request->all());

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return $this->userService->list_one($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, $id)
    {


        return $this->userService->update($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
       return $this->userService->delete($id);
    }

    public function update_workaround(UserRequest $request, $id)
    {
        return $this->update($request, $id);
    }


    public function getDashboardMenu(Request $request){
        $request->user()->role_id;
        $filePath = storage_path('/resource/dashboard.json');
        $jsonContent = file_get_contents($filePath);
        $array =  json_decode($jsonContent, true);
        $menu = [];
        foreach ($array["pages"] as $element){
           if (in_array($request->user()->role_id, $element["permission"])){
               array_push($menu, $element);
           }
        }
        return $menu;
    }
}
