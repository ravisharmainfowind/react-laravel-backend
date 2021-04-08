<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 
use App\Http\Resources\UserResource;
use App\Models\{Role};
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index(Request $request)
    {
         $data['roles'] = Role::get();
         if($data){
         	return response()->json([
            'success'   => true,
            'message'   => "Successfully data found.",
            'data'      => $data,
         ]);
        }
        else{ 
         return response()->json([
            'success'   => false,
            'message'   => "No data found.",
        ]);
       }  
    }

    public function store(Request $request) {

         $data = $request->validate([
            'name' => 'required|string|max:255',
          ]);
        $data = [
            'name'   => $request->get('name'),
            'status' =>  ($request->get('status')== 1) ? ('1') : ('0'),
        ];
        $type = Role::create($data);
        if($type) {
            return response()->json([
                'success'   => true,
                'message'   => "Successfully Add Role.",
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'message'   => "Invalid credentilas",
            ]);
        }
    }

    public function edit(Request $request, $id)
	{
	   $data['role_info'] = Role::where('id', $id)->first();
        return response()->json([
            'success'   => true,
            'message'   => "Successfully data found.",
            'data'      => $data,
        ]);
	}

	public function update($id, Request $request)
    {
       $data = $request->validate([
            'name' => 'required|string|max:255',
          ]);

        $data = [
            'name'   => $request->get('name'),
            'status' =>  ($request->get('status')== 1) ? ('1') : ('0'),
        ];

     $update_data = Role::where('id',$id)->update($data);
     if($update_data) {
            return response()->json([
                'success'   => true,
                'message'   => "Successfully Update Role",
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'message'   => "Invalid credentilas",
            ]);
        }
    }
    public function delete($id){
     $where = array('id' => $id);
     $result = Role::where($where)->delete();
     if($result) {
            return response()->json([
                'success'   => true,
                'message'   => "Successfully Delete Role.",
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'message'   => "Invalid credentilas",
            ]);
        }
    }
    public function changeStatus(Request $request,$id){
      
       $data['role_info'] = $role = Role::where('id', $id)->first();
       if($role->status=='0'){
       	 $data = [
            'status' => '1',
        ];
       }
       else{
       	 $data = [
            'status' => '0',
        ];
       }
       $update_data = Role::where('id', $id)->update($data);
        if($update_data) {
            return response()->json([
                'success'   => true,
                'message'   => "Successfully Change Status.",
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'message'   => "Invalid credentilas",
            ]);
        }
    }
}
