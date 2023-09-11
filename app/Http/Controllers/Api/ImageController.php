<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ImageStore;
use App\Models\Image;
use App\Http\Resources\ImageResource;
use Illuminate\Support\Facades\File;
use App\Traits\ApiResponseTrait;


class ImageController extends Controller
{

    use ApiResponseTrait;


    public function index(){

      $images = ImageResource::collection(Image::get());
        return $this->apiResponse($images ,'' , 200);
    }

    public function show($id){

        $image = Image::find($id);
        if( $image){
            return $this->apiResponse(new ImageResource($image) ,'ok' , 200);
        }
        return $this->apiResponse(null,'the image not found' , 404);
    }

     //store
     public function store(ImageStore $request){


        if($request->has('fileName')){
            $image = new Image();
            $getImage = $request->fileName;
            $name = time().'.'.$getImage->getClientOriginalExtension();
            $path = public_path().'/uploads';
            $image->fileName =$name ;
            $getImage->move( $path , $name );
            $image->post_id = $request->post_id;
            $image->save();

            return $this->apiResponse(new ImageResource($image), 'Image Uploaded Successfuly', 201);
        }
    }

    //update
    public function update(ImageStore $request , $id){

        $image = Image::find($id);

            if($image) {
                $oldImage= $image->fileName;
                $getImage = $request->fileName;
                $name = time().'.'.$getImage->getClientOriginalExtension();
                $path = public_path().'/uploads';
                $image->fileName =$name ;
                $getImage->move($path, $name);
                $image->post_id = $request->post_id;
                $image->save();
                File::delete(public_path().'/uploads/'.$oldImage);
                return $this->apiResponse(new ImageResource($image), 'the image update', 201);
            }

        return $this->apiResponse(null, 'the image not found', 404);
    }

    //delete
    public function destroy( $id)
    {
        $image = Image::findOrFail($id);
        if($image) {
            File::delete(public_path().'/uploads/'.$image->fileName);
            $image->delete($id);
            return $this->apiResponse(null ,'the image deleted', 200);
        }

        return $this->apiResponse(null, 'the image not found', 404);

    }






}
