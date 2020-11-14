<?php


namespace App\Http\Controllers;


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

        $credentials = $request->only(['email', 'password']);
        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'QA leshi'], 401);
        }

        return $this->respondWithToken($token);
    }
    public function me(Request $request){
        $user=User::with('user_details')->findOrFail(Auth::id());



        return response()->json(['user'=>$user],200);
    }
}
