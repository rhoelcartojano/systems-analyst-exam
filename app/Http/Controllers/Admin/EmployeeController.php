<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AccessUtilitiesModel as AccessUtilities;
use App\Models\Admin\DepartmentModel as Department;
use App\Models\Admin\EmployeeModel as Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employee = Employee::with([
            'department',
            'access_utility'
        ])->get();
 
        return response()->json([
            'code' => '200',
            'status' => 'Success',
            'body' => $employee
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|numeric',
            'name' => 'required|string|max:255',
            'email' => 'required|max:255|string|unique:employees',
            'access_utilities' => empty(json_decode($request->access_utilities)) ? 'required|array|min:1' : 'min:1',
            'password' => 'required|string|min:8|max:255'
        ]);

        $employee = Employee::create([
            'department_id' => $request->department_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        AccessUtilities::create([
            'employee_id' => $employee->getKey(),
            'access_utilities' => $request->access_utilities
        ]);

        return response()->json([
            'code' => '200',
            'status' => 'Success',
            'id' => $employee->getKey(),
            'body' => $employee->with([
                    'department',
                    'access_utility'
                ])->get()
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
        $employee = Employee::with([
            'department',
            'access_utility'
        ])->where('id', $id)
        ->get();

        return response()->json([
            'code' => '200',
            'status' => 'Success',
            'body' => $employee,
            'departments' => Department::get()
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
            'department_id' => 'required|numeric',
            'name' => 'required|max:255',
            'access_utilities' => empty(json_decode($request->access_utilities)) ? 'required|array|min:1' : 'min:1',
        ]);

        $employee = Employee::find($id);
        $employee->fill([
            'department_id' => $request->department_id,
            'name' => $request->name
        ])->save();
        
        $access_utilities = AccessUtilities::where('employee_id', $employee->getKey())->first();
        $access_utilities->fill([
            'access_utilities' => $request->access_utilities
        ])->save();

        return response()->json([
            'code' => '200',
            'status' => 'Success',
            'body' => $employee->with([
                'department',
                'access_utility'
            ])->get()
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
        $employee = Employee::find($id);
        $employee->delete();

        return response()->json([
            'code' => '200',
            'status' => 'Success'
        ]);
    }

    public function exportCsv(Request $request)
    {

        $fileName = 'Report Inquiry '. date('Y-m-d') .'.csv';

        $employees = Employee::with([
            'department',
            'access_utility'
        ])->get();;

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Department Name', 'Employee Name', 'Access Utility', 'Date');

        $callback = function() use($employees, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($employees as $employee) {
                $row['DepartmentName']  = $employee->department['name'];
                $row['EmployeeName']    = $employee->name;
                $row['AccessUtilitiy'] = $employee->access_utility['access_utilities'];
                $row['Date']  = $employee->created_at;

                fputcsv($file, array($row['DepartmentName'], $row['EmployeeName'], $row['AccessUtilitiy'], $row['Date']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
