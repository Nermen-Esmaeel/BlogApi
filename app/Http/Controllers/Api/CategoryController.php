<?php

namespace App\Http\Controllers\Api;

use App\Models\Catergory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoryResource;
use App\Traits\ApiResponseTrait;

class CategoryController extends Controller
{

    use ApiResponseTrait;

    public function index(){
        //fetch all categories from database and store in $categories
      $categories = CategoryResource::collection(Catergory::get());
        return $this->apiResponse($categories ,'' , 200);
    }


     //fetch  category from database
    public function show($id){

        $category = Catergory::find($id);
        if( $category){
            return $this->apiResponse(new CategoryResource($category) ,'ok' , 200);
        }
        return $this->apiResponse(null,'the category not found' , 404);
    }

    //store new category in database
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'title' =>'required|string',
        ]);
        if($validator->fails()){
            return $this->apiResponse(null, $validator->errors()  , 400);
        }
        $category = Catergory::create($request->all());

        if($category) {
            return $this->apiResponse(new CategoryResource($category), 'ok', 201);
        }

    }


    public function update(Request $request , $id){

        //validate request
        $validator = Validator::make($request->all(), [
            'name' =>'required|string',

        ]);
        if($validator->fails()){
            return $this->apiResponse(null, $validator->errors()  , 400);
        }

        //check if record exist
        $category = Catergory::find($id);
        if($category) {
            $category->update($request->all());
            return $this->apiResponse(new CategoryResource($category), 'the category update successfuly', 201);
        }
        return $this->apiResponse(null, 'the category not found', 404);
    }

    //delete category
    public function destroy( $id)
    {
        $category = Catergory::find($id);
        if($category) {

            $category->delete($id);
            return $this->apiResponse(null ,'the category deleted successfuly', 200);
        }

        return $this->apiResponse(null, 'the category not found', 404);

    }

}
