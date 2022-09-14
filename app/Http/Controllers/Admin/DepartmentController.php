<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\DepartmentModel as Department;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $department = Department::get();
 
        return response()->json([
            'code' => '200',
            'status' => 'Success',
            'body' => $department
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255|unique:departments',
            'description' => 'max:255'
        ]);

        $department = Department::create($data);
        return response()->json([
            'code' => '200',
            'status' => 'Success',
            'id' => $department->getKey()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $department = Department::where('id', $id)->get();
        return response()->json([
            'code' => '200',
            'status' => 'Success',
            'body' => $department
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'max:255'
        ]);

        $department = Department::find($id);
        $department->fill([
            'name' => $request->name,
            'description' => $request->description
        ])->save();

        return response()->json([
            'code' => '200',
            'status' => 'Success',
            'body' => $department
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $department = Department::find($id);
        $department->forceDelete();

        return response()->json([
            'code' => '200',
            'status' => 'Success'
        ]);
    }
}
