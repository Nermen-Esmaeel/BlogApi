<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class UserController extends Controller
{
      //login a User.
      public function login(Request $request){

    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }

    // Register a User.
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $user = User::create(array_merge(
                    $validator->validated(),
                    ['password' => bcrypt($request->password)]
                ));
        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    //Log the user out (Invalidate the token)
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

 //user Profile
 public function userProfile() {
    return response()->json(auth()->user());
}



//update user Profile
public function updateProfile(Request $request , $id) {

    $input = $request->all();
    $user = User::find($id);

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|between:2,100',
        'email' => 'required|string|email|max:100',
        'password' => 'nullable|string|min:8',
    ]);
    if($validator->fails()){
        return response()->json($validator->errors()->toJson(), 400);
    }

        if(!empty($input['password'])){
        $input['password'] = $input['password'] ;
        }else{
        $input = array_except($input,array('password'));
        }
        $user->update($input);

        return response()->json([
            'message' => 'User Updated registered',
            'user' => $user
        ], 201);
    
}


    //Create New Token
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            //Token Expired
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    
//change plan
public function changePlan(Request $request , $id){

    $user = User::find($id);
    if($user){
        $user->subscription_plan = $request->subscription_plan;
        $user->save();

        return response()->json([
            'message' => 'Subscription Plan Has Been Changed To '.$user->subscription_plan,
        ], 201);
    }

      return response()->json(['data' => null, 'message' => 'the user not found'], 404);

    }
   
  
}

