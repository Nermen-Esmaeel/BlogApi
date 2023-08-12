<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\TagResource;

class TagController extends Controller
{

    use ApiResponseTrait;




    public function index(){
        //fetch all tags from database and store in $tags
      $tags = TagResource::collection(Tag::get());
        return $this->apiResponse($tags ,'' , 200);
    }


     //fetch  category from database
    public function show($id){

        $tag = Tag::find($id);
        if( $tag){
            return $this->apiResponse(new TagResource($tag) ,'ok' , 200);
        }
        return $this->apiResponse(null,'the tag not found' , 404);
    }

    //store new category in database
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'name' =>'required|string',
        ]);
        if($validator->fails()){
            return $this->apiResponse(null, $validator->errors()  , 400);
        }
        $tag = Tag::create($request->all());

        if($tag) {
            return $this->apiResponse(new TagResource($tag), 'ok', 201);
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
        $tag = Tag::find($id);
        if($tag) {
            $tag->update($request->all());
            return $this->apiResponse(new TagResource($tag), 'the tag update successfuly', 201);
        }
        return $this->apiResponse(null, 'the tag not found', 404);
    }

    //delete category
    public function destroy( $id)
    {
        $tag = Tag::find($id);
        if($tag) {

            $tag->delete($id);
            return $this->apiResponse(new TagResource($tag), 'the tag deleted successfuly', 200);
        }

        return $this->apiResponse(null, 'the tag not found', 404);

    }




    public function tagPosts(Tag $tag)
    {

        $posts = $tag->posts;
        dd($posts);
        return response()->json(['message'=>null,'data'=>$posts],200);
    }

}
