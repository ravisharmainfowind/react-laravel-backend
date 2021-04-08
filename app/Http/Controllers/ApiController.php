<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Image;
use Validator;

class ApiController extends Controller
{

  public function createEmployee(Request $request) {
  
    $request->validate([
      'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
   ]);
    
    if($request->profile_image){ 
       $imageName = time().'.'.$request->profile_image->extension();
       $request->profile_image->move(public_path('profile_image'), $imageName);
    }else{
       $imageName = 'user.jpg';
    }

    $employee = new Employee;
    $employee->employee_name = $request->employee_name;
    $employee->employee_salary = $request->employee_salary;
    $employee->employee_age = $request->employee_age;
    $employee->mobile_number = $request->mobile_number;
    $employee->profile_image = $imageName;
    $employee->gender = $request->gender;
    $employee->address = $request->address;
    $employee->email = $request->email;
    $employee->date = date('Y-m-d', strtotime($request->get('date')));
    $employee->save();

    return response()->json([
        "message" => "employee record created"
    ], 201);
  }

  public function getAllEmployees() {
    $employees = Employee::get()->toJson(JSON_PRETTY_PRINT);
    return response($employees, 200);
  }

  public function getEmployee($id) {
    if (Employee::where('id', $id)->exists()) {
        $employee = Employee::where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
        return response($employee, 200);
      } else {
        return response()->json([
          "message" => "Employee not found"
        ], 404);
      }
  }


  public function updateEmployee(Request $request, $id) {
    if (Employee::where('id', $id)->exists()) {
        $employee = Employee::find($id);
        //print_r($request->profile_image);
        if($request->profile_image){ 
          $imageName = time().'.'.$request->profile_image->extension();
          $request->profile_image->move(public_path('profile_image'), $imageName);   
       }else{
          $imageName = $employee->profile_image;
       }
        $employee->employee_name = is_null($request->employee_name) ? $employee->employee_name : $request->employee_name;
        $employee->employee_salary = is_null($request->employee_salary) ? $employee->employee_salary : $request->employee_salary;
        $employee->employee_age = is_null($request->employee_age) ? $employee->employee_age : $request->employee_age;
        $employee->mobile_number = is_null($request->mobile_number) ? $employee->mobile_number : $request->mobile_number;
        $employee->address = is_null($request->address) ? $employee->address : $request->address;
        $employee->email = is_null($request->email) ? $employee->email : $request->email;
        $employee->profile_image = $imageName;
        $employee->gender = is_null($request->gender)? $employee->gender : $request->gender;
        $employee->date = is_null($request->date)?$employee->date : date('Y-m-d', strtotime($request->get('date')));
        $employee->save();

        return response()->json([
            "message" => ["records updated successfully"]
        ], 200);
        } else {
        return response()->json([
            "message" => "employee not found"
        ], 404);
        
    }
  }

  public function deleteEmployee ($id) {
      if(Employee::where('id', $id)->exists()) {
        $employee = Employee::find($id);
        $employee->delete();

        return response()->json([
          "message" => "records deleted"
        ], 202);
      } else {
        return response()->json([
          "message" => "Employee not found"
        ], 404);
      }
    }

    public function uploadFile(Request $request){
      //print_r($request->myFile);die;
      if($request->myFile){ 
        $imageName = time().'.'.$request->myFile->extension();
        $request->myFile->move(public_path('profile_image'), $imageName);
       }
       return response()->json([
        "image_name" => $imageName
    ], 201); 
    }

  public function store(Request $request){
     
    if(!(object)$request->file('fileName')) {
        return response()->json(['upload_file_not_found'], 400);
    }
 
    $allowedfileExtension=['pdf','jpeg','jpg','png'];
    $files = (object)$request->file('fileName'); 
    $errors = [];
 
    foreach ($files as $file) {          
        $extension = $file->getClientOriginalExtension();
        $check = in_array($extension,$allowedfileExtension);
        if($check) {
            foreach($request->fileName as $mediaFiles) {
                $path = $mediaFiles->store('public/images');
                $name = $mediaFiles->getClientOriginalName();
                //store image file into directory and db
                $save = new Image();
                $save->title = $name;
                $save->path = $path;
                $save->save();
            }
        } else {
            return response()->json(['invalid_file_format'], 422);
        }
        return response()->json(['file_uploaded',$files], 200);
    }
  }
}
