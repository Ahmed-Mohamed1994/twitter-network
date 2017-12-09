<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Like;
use App\mention;
use App\Tweet;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TweetController extends Controller
{
    public function postCreateTweet(Request $request)
    {
        $this->validate($request, [
            'new-tweet' => 'required|max:1000'
        ]);
        $tweet = new Tweet();
        $tweet->body = $request['new-tweet'];
        $tweet->userId = Auth::user()->id;
        if ($tweet->save()) {
            return redirect()->route('dashboard')->with(['message' => 'Your Tweet Successfully Created!']);
        }
    }

    public function getEditTweet(Tweet $tweetId)
    {
        return view('edit_tweet')->with(['tweet' => $tweetId]);
    }

    public function postEditTweet(Request $request)
    {
        $this->validate($request, [
            'new-tweet' => 'required|max:1000'
        ]);
        $tweet_id = $request['tweet_id'];
        $tweet = Tweet::find($tweet_id);
        if (!$tweet) {
            return redirect()->back()->with(['message_err' => 'Tweet Not Found!']);
        }
        $tweet->body = $request['new-tweet'];
        $tweet->update();
        return redirect()->route('news-feed')->with(['message' => 'Your tweet Successfully Updated!']);
    }

    public function getDeleteTweet($tweetId)
    {
        $tweet = Tweet::where('id', $tweetId)->first();
        if (Auth::user()->id != $tweet->userId) {
            return redirect()->back();
        }
        $tweet->delete();
        return redirect()->route('news-feed')->with(['message' => 'Tweet successfully deleted!']);
    }

    public function postLikeTweet(Request $request){
        $tweet_id = $request['tweetId'];
        $is_like_text = $request['isLikeText'];
        $tweet = Tweet::where('id', $tweet_id)->first();
        if(!$tweet){
            return null;
        }
        $user = Auth::user();
        $like = $user->likes()->where('tweet_id' , $tweet_id)->first();
        if($like && $is_like_text=="DisLike"){
            $like->delete();
            return null;
        }else{
            $like = new Like();
            $like->user_id = $user->id;
            $like->tweet_id = $tweet->id;
            $like->save();
            return null;
        }
    }

    public function postAddComment(Request $request){
        $this->validate($request,[
            'tweet_comment' => 'required|max:1000'
        ]);
        $tweet_id = $request['tweet_comment_id'];
        $comment_body = $request['tweet_comment'];
        $user = Auth::user();
        $comment = new Comment();
        $comment->tweet_id = $tweet_id;
        $comment->user_id = $user->id;
        $comment->comment = $comment_body;
        $comment->save();
        $explode_at = explode("@", $comment_body ) ;
        $get_username = explode(" ", $explode_at[1] );
        if($get_username != ""){
            $mention_user = User::where('username' , $get_username)->first();
            if($mention_user){
                $last_comment_id = $comment->id;
                $new_mention = new mention();
                $new_mention->user_id = $user->id;
                $new_mention->mention_user_id = $mention_user->id;
                $new_mention->tweet_id = $tweet_id;
                $new_mention->comment_id = $last_comment_id;
                $new_mention->mention_username = $mention_user->username;
                $new_mention->save();
            }
        }
        return redirect()->route('news-feed')->with(['message' => "Comment successfully Added!"]);
    }
}
