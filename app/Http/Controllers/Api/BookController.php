<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(
        protected  BookService $bookService,
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->bookService->list();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        return $this->bookService->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->bookService->listOne($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, string $id)
    {
        return $this->bookService->edit($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->bookService->delete($id);
    }

    public function update_workaround(BookRequest $request, $id)
    {
        return $this->update($request, $id);
    }

    public function filter(Request $request)
    {

        return $this->bookService->filter(
            $request->input('title'), 
            $request->input('author'),
            $request->input('gender')
        );
    }
}
