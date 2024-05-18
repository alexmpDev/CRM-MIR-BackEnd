<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cursos = Course::all();
        return response()->json($cursos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'curs' => 'required|string|max:255',
        ]);
    
        $course = Course::create([
            'curs' => $validatedData['curs']
        ]);
     
        return response()->json($course, 201);
    }
    
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'curs' => 'required|string|max:255',
        ]);

        $course = Course::findOrFail($id);

        $course->update([
            'curs' => $validatedData['curs']
        ]);

        return response()->json($course, 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
    
        return response()->json(['message' => 'Course deleted successfully'], 200);
    }
    
}
