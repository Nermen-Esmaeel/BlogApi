<?php

namespace App\Http\Controllers\Api;

use App\Models\{Catergory,Image};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoryResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{

    use ApiResponseTrait;

    public function index(){
        $categories = Catergory::query();
        //fetch all categories from database and store in $categories
        return $this->apiResponse(CategoryResource::collection( $categories->with(['images'])->get()) ,'' , 200);
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
            'name' =>'required|string',
        ]);
        if($validator->fails()){
            return $this->apiResponse(null, $validator->errors()  , 400);
        }

        $category = Catergory::create($request->all());

        if ($request->hasFile('images')) {

            foreach ($request->file('images') as $image) {

                //call helpers UploadImage
                $path = UploadImage('images/Category', $image);
                $category->images()->create([
                    'url' => $path,
                ]);
            }
        }

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

            //images handling
            if ($request->hasFile('images')) {

                //delete old Images from folder
                foreach ( $category->images as $image){

                    DeleteImageFromStorage($image->url);
                }

                foreach ($request->file('images') as $image) {

                   //call helpers UploadImage
                    $path = UploadImage('images/Category', $image);
                    $category->images()->update([
                        'url' => $path,
                    ]);
                }


        }
            $category->update($request->all());

            return $this->apiResponse(new CategoryResource($category), 'the category update successfuly', 201);
        }
        return $this->apiResponse(null, 'the category not found', 404);
    }

    //delete category
    public function destroy( $id)
    {
        $category = Catergory::findOrFail($id);
        if($category) {

            //check if category have images
            if ($category->images) {
                //delete old Images from folder
                foreach ( $category->images as $image){

                    DeleteImageFromStorage($image->url);
                    $category->images()->delete();
                }
            }
           $category->delete($id);
            return $this->apiResponse(null ,'the category deleted successfuly', 200);
        }

        return $this->apiResponse(null, 'the category not found', 404);

    }

}
