<?php


namespace App\Http\Controllers;


use App\Models\Post;
use App\Models\Reply;
use App\Models\User;
use App\Models\User_Details;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PostsController extends Controller
{
    public function getReplies(Request $request,$post_id) {
        $post_with_replies=Post::with('replies')->findOrFail($post_id);

        return response()->json(['post_with_replies'=>$post_with_replies],200);
    }

    public function postReply(Request $request,$post_id) {
        Post::findOrFail($post_id);
        $user_id=Auth::id();

        try {
            $this->validate($request,[
                'description'=>"required|string"
            ]);

            $reply=new Reply;
            $reply->user_id=$user_id;
            $reply->post_id=$post_id;
            $reply->description=$request->input('description');

            $reply->save();

            return  response()->json(['message'=>'Reply created','reply'=>$reply],200);

        }
        catch (\Exception $e) {
            return response()->json(['message'=>'Bad Request'],400);
        }
    }
}
