<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\{Category,image_multiple,Product};
use Illuminate\Validation\Rule;
use App\Services\Slug;
use Validator, Session, Redirect, Response, DB, Config, Auth,Image;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {  
         $data['products'] = Product::get();
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
        $data['category_info'] = Category::where('status', 1)->get();		
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

    public function delete($id){
        $where = array('id' => $id);
        $result = Product::where($where)->delete();
        if($result) {
               return response()->json([
                   'success'   => true,
                   'message'   => "Successfully Delete Product.",
               ]);
           } else {
               return response()->json([
                   'success'   => false,
                   'message'   => "Invalid credentilas",
               ]);
           }
       }
       public function changeStatus(Request $request,$id){
         
          $data['product_info'] = $product = Product::where('id', $id)->first();
          if($product->status=='0'){
               $data = [
               'status' => '1',
           ];
          }
          else{
               $data = [
               'status' => '0',
           ];
          }
          $update_data = Product::where('id', $id)->update($data);
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

    public function store(Request $request) {
        
        $data = $request->validate([
           'title'        => 'required|string|max:255',
           'category_id'  => 'required',
           'quantity'     => 'required|numeric',
           'price'        => 'required|numeric',
           'sale_price'   => 'required|numeric',
         ]);

         // On create
          //$data['slug']  = $slug->createSlug($request->title);
       
       $data = [
           'title'          => $request->get('title'),
           'category_id'    => $request->get('category_id'),
           'quantity'       => $request->get('quantity'),
           'price'          => $request->get('price'),
           'sale_price'     => $request->get('sale_price'),
           'description'    => $request->get('description'),
           'slug'           => Str::slug($request->title),
           'status'         =>  ($request->get('status')== 1) ? ('1') : ('0'),
       ];
       
       $last_insert_id = Product::insertGetId($data);

        if($last_insert_id && $request->file('image')) {
        $allowedfileExtension=['pdf','jpeg','jpg','png'];
        $files = $request->file('image'); 
        $errors = [];
       
        foreach ($files as $file) {        
            $extension = $files->getClientOriginalExtension();
            $check = in_array($extension,$allowedfileExtension);
           
            if($check) { 
                foreach($request->image as $mediaFiles) {
                    $path = $mediaFiles->store('public/product');
                    $name = $mediaFiles->getClientOriginalName();
                    //store image file into directory and db
                    $save = new image_multiple();
                    $save->images = $path.$name;
                    $save->product_id = $last_insert_id;
                    //$save->path = $path;
                    $save->save();
                }
            } else {
                return response()->json(['invalid_file_format'], 422);
            }
          }
        }

       if($last_insert_id) {
           return response()->json([
               'success'   => true,
               'message'   => "Successfully Add Product.",
           ]);
       } else {
           return response()->json([
               'success'   => false,
               'message'   => "Invalid credentilas",
           ]);
       }
    
    }

}
