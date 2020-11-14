<?php


namespace App\Http\Controllers;
use App\Models\Admin;
use App\Models\Post;
use App\Models\User;
use App\Models\User_Details;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'

        ]);
        try {
            $admin = new Admin;
            $admin->name = $request->input('name');
            $admin->email = $request->input('email');
            $admin->password = app('hash')->make($request->input('password'));

            $admin->save();

            return response()->json(['admin' => $admin, 'message' => 'CREATED'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Admin Registration failed'], 409);
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
        config()->set( 'auth.defaults.guard', 'admin' );
        config()->set( 'auth.defaults.passwords', 'admins' );
        $credentials = $request->only(['email', 'password']);
        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Not Authorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout(Request $request)
    {
        //validate incoming request
        Auth::logout();
        return response()->json(['message' => 'logout'], 200);
    }


    public function deletePost(Request $request, $post_id)
    {
        try {
            $post = Post::findOrFail($post_id);

            $post->delete();
            return response()->json(['message'=>'deleted post by admin','post'=>$post], 200);
        } catch (\Exception $e) {
            return response()->json([], 500);
        }
    }
}

