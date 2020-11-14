<?php


namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
class UserController extends Controller
{

    public function register(Request $request) {
        $this->validate($request,[
            'name'=>'required|string',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed'

        ]);
        try {
            $user=new User;
            $user->name=$request->input('name');
            $user->email=$request->input('email');
            $user->password=app('hash')->make($request->input('password'));

            $user->save();

            return response()->json(['user'=>$user,'message'=>'CREATED'],201);
        }
        catch (\Exception $e) {
            return  response()->json(['message'=>'User Registration failed'],409);
        }
    }

}
