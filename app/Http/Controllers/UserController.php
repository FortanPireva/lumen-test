<?php


namespace App\Http\Controllers;


use App\Models\Post;
use App\Models\User;
use App\Models\User_Details;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{


    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'

        ]);
        try {
            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = app('hash')->make($request->input('password'));

            $user->save();

            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'User Registration failed'], 409);
        }
    }

    public function login(Request $request)
    {
        //validate incoming requests

        try {
            $this->validate($request, [
                'email' => 'required|string',
                'password' => 'required|string'
            ]);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Invalid Login Info'], 409);
        }
        config()->set( 'auth.defaults.guard', 'api' );
        config()->set( 'auth.defaults.passwords', 'users' );
        $credentials = $request->only(['email', 'password']);
        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'QA leshi'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout(Request $request)
    {
        //validate incoming request
        Auth::logout();
        return response()->json(['message'=>'logout'],200);
    }
    public function me(Request $request){
        $user=User::with('user_details')->findOrFail(Auth::id());



        return response()->json(['user'=>$user],200);
    }
    public function createPost(Request $request) {
        try {
            $this->validate($request,[
               'title'=>'required|string',
                'image_url'=>'required:string',
                'description'=>'required:string'
            ]);


            $post=new Post;

            $post->title=$request->input('title');
            $post->image_url=$request->input('image_url');
            $post->description=$request->input('description');
            $post->user_id=Auth::id();

            $post->save();

            return response()->json(['message'=>'Post created','id'=>$post->id],200);
        }
        catch (\Exception $e) {
            return response()->json(['message'=>$e],500);
        }
    }

    public function updatePost(Request $request,$post_id) {
        $post=Post::findOrFail($post_id);
        if ($post->user_id!=Auth::id()){
            return response('Unauthorized.', 401);
        }
        $this->validate($request,[
            'title'=>'required|string',
            'image_url'=>'required:string',
            'description'=>'required:string'
        ]);
        $post->title=$request->input('title');
        $post->image_url=$request->input('image_url');
        $post->description=$request->input('description');


        $post->save();

        return response()->json(['message'=>'Post updated','post'=>$post],200);
    }
    public function deletePost(Request $request,$post_id) {
        try {
            $post=Post::findOrFail($post_id);

            $post->delete();
            return response()->json([],200);
        }
        catch (\Exception $e) {
            return response()->json([],500);
        }
    }
}
