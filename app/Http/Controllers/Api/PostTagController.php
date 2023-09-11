<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Traits\ApiResponseTrait;

class PostTagController extends Controller
{

    use ApiResponseTrait;
    //
    public function addTagsForPost(Request $request , $id){
        //store post's tags
        $post = $post = Post::find($id);
        //Sync : if tag exist to post keep it or don't exist add it without duplicated (pivot table)
        $post->tags()->syncWithoutDetaching($request->tags);
       $post_tag = $post->load('tags');
        return $this->apiResponse(  $post_tag ,'Ok', 201);
    }

    public function deleteTagForPost(Request $request , $id){
        //store post's tags
        $post = $post = Post::find($id);
        //detach : delete tag from post
        $post->tags()->detach($request->tags);

        return $this->apiResponse( null,'the tag deleted successfuly', 200);

    }

    public function show($id){

        $post = $post = Post::find($id);
       return  $post->load('tags');



    }
}
