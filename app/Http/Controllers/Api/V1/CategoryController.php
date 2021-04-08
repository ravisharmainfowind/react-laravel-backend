<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\{Category};
use Illuminate\Validation\Rule;
use Validator, Session, Redirect, Response, DB, Config, Auth;
use Illuminate\Support\Str;


class CategoryController extends Controller
{
    public function index(Request $request)
    {  
         $data['categories'] = Category::get();
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

    public function create(Request $request){
        $data['category_info'] = Category::where('parent_id', 0)->get();		
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
           'image' => 'required',
         ]);
         
            if($request->get('parent_id')){
                $parentId = $request->get('parent_id');
            }  
            else{
                $parentId = 0;
            }
        //    $slug = Str::slug($request->name);
        //    $data['slug'] = $slug;
       
       $data = [
           'name'   => $request->get('name'),
           'parent_id'   => $parentId,
           'slug'            => Str::slug($request->name),
           'status' =>  ($request->get('status')== 1) ? ('1') : ('0'),
       ];
       
        $files = $request->file('image');
        
        if($request->image){ 
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('category_image'), $imageName);
            $data['image'] = $imageName;
           }

        if($request->image_icon){ 
        $imageName = time().'.'.$request->image_icon->extension();
        $request->image_icon->move(public_path('category_image_icon'), $imageName);
        $data['image_icon'] = $imageName;
        }   
       $type = Category::create($data);
       if($type) {
           return response()->json([
               'success'   => true,
               'message'   => "Successfully Add Category.",
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
	   $data['category_info'] = Category::where('id', $id)->first();
       $data['category_parent_info'] = Category::where('parent_id', 0)->get();
        return response()->json([
            'success'   => true,
            'message'   => "Successfully data found.",
            'data'      => $data,
        ]);
	}

    public function update($id, Request $request)
    {
       $data = $request->validate([
            //'name' => 'required|string|max:255',
          ]);
        $data['category_info'] =$category_info =Category::where('id', $id)->first();

        if($request->get('parent_id')){
            $parentId = $request->get('parent_id');
        }  
        else{
            $parentId = $category_info->parent_id;
        } 

        if($request->get('name')){
            $name = $request->get('name');
            //$data['slug']  = Str::slug($name);
        }  
        else{
            $name = $category_info->name;
            //$data['slug']  = Str::slug($name);
        } 
      
        $data = [
            'name'            => $name,
            'slug'            => Str::slug($request->name),
            'parent_id'       => $parentId,
            'status'          => $category_info->status,
            'updated_at'      => date('Y-m-d H:i:s'),
        ];
        
        if($request->image){ 
            $imageName = time().'.'.$request->image->extension();
            $request->image->move(public_path('category_image'), $imageName);
            $data['image'] = $imageName;
           }

        if($request->image_icon){ 
        $imageName = time().'.'.$request->image_icon->extension();
        $request->image_icon->move(public_path('category_image_icon'), $imageName);
        $data['image_icon'] = $imageName;
        } 

     $update_data = Category::where('id',$id)->update($data);
     if($update_data) {
            return response()->json([
                'success'   => true,
                'message'   => "Successfully Update Category",
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
    $result = Category::where($where)->delete();
    if($result) {
           return response()->json([
               'success'   => true,
               'message'   => "Successfully Delete Category.",
           ]);
       } else {
           return response()->json([
               'success'   => false,
               'message'   => "Invalid credentilas",
           ]);
       }
   }
   public function changeStatus(Request $request,$id){
     
      $data['category_info'] = $category = Category::where('id', $id)->first();
      if($category->status=='0'){
           $data = [
           'status' => '1',
       ];
      }
      else{
           $data = [
           'status' => '0',
       ];
      }
      $update_data = Category::where('id', $id)->update($data);
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
