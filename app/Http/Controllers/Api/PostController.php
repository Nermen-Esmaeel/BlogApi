<?php

namespace App\Http\Controllers\Api;

use App\Models\{Post,Image};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PostResource;
use App\Http\Requests\PostStore;
use App\Traits\ApiResponseTrait;



class PostController extends Controller
{
    use ApiResponseTrait;

    //
    public function index(Request $request)
    {

        $post_type = $request->post_type;
        //fetch all posts from database and store in $posts
        $post_list = Post::where('type' , $post_type)->with(['images'])->get();
        $posts = PostResource::collection( $post_list);

        return $this->apiResponse($posts, '', 200);
    }

    public function show($id)
    {
        //fetch  post from database and store in $posts

        $post = Post::find($id)->with(['images']);
        if($post) {
            return $this->apiResponse(new PostResource($post), 'ok', 200);
        }
        return $this->apiResponse(null, 'the post not found', 404);
    }

    //store
    public function store(PostStore $request)
    {

        $post = new Post();
        $post->title = $request->title;
        $post->content =$request->content;
        $post->slug =$request->slug;
        $post->user_id = Auth::user()->id;
        $post->category_id = $request->category_id;
        $post->type = $request->type;
        $post->save();

        if ($request->hasFile('images')) {

            // $path = $this->UploadFile('Article_Covers', $request->file('article_cover'));
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('images/post', $filename);


                $post->images()->create([
                    'url' => $path,
                ]);
            }
        }

        if($post) {
            return $this->apiResponse(new PostResource($post), 'ok', 201);
        }

    }
    //update
    public function update(PostStore $request, $id)
    {

        $post = Post::find($id);

        if($post) {

            if ($request->hasFile('images')) {

                foreach ($request->file('images') as $image) {
                    // Storage::disk('public')->delete('Categories/' . $image->image);
                    $filename = time() . '_' . $image->getClientOriginalName();

                    $path =  $image->storeAs('images/post', $filename);

                    $newImage = new Image(['url' => $path]);
                    $post->images()->save($newImage);
                }
            }


            $post = new Post();
            $post->title = $request->title;
            $post->content =$request->content;
            $post->slug =$request->slug;
            $post->user_id = Auth::user()->id;
            $post->category_id = $request->category_id;
            $post->type = $request->type;
            $post->save();
            return $this->apiResponse(new PostResource($post), 'the post update', 201);
        }
        return $this->apiResponse(null, 'the post not found', 404);
    }

    //delete
    public function destroy($id)
    {
        $post = Post::find($id);
        if($post) {

            $post->delete($id);
            return $this->apiResponse(null, 'the post deleted', 200);
        }

        return $this->apiResponse(null, 'the post not found', 404);

    }

    //search
    public function search($name)
    {
       $result = Post::where("title", "like", "%".$name."%")->get();
       if(count($result)){
        return $this->apiResponse($result, 'ok', 201);
       }else{
        return $this->apiResponse(null, 'There is no post title it like '.$name , 404);
       }

    }


}
