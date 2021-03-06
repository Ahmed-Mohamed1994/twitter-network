<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Follow;
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
            return redirect()->route('news-feed')->with(['message' => 'Your Tweet Successfully Created!']);
        }
    }

    public function getTweet(Tweet $tweetId)
    {
        if(Auth::user()->id != $tweetId->userId){
            $followed_user = Follow::where(['userId'=> Auth::user()->id , 'Follow_userId' => $tweetId->userId])->first();
            if(!$followed_user){
                return redirect()->route('activity-feed')->with(['message_err' => 'You can\'t view this tweet!']);
            }
        }
        $user_post = User::where('id', $tweetId->userId)->first();
        $comments_tweet = Comment::where('tweet_id', $tweetId->id)->get();
        return view('tweet')->with(['tweet' => $tweetId, 'comments' => $comments_tweet, 'user_post' => $user_post]);
    }

    public function getEditTweet(Tweet $tweetId)
    {
        if(Auth::user()->id != $tweetId->userId){
            return redirect()->back()->with(['message_err' => 'Please Update Your Tweets Only!']);
        }
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
        if(Auth::user()->id != $tweet->userId){
            return redirect()->back();
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
        $get_comments = Comment::where('tweet_id', $tweet->id)->get();
        foreach ($get_comments as $current_comment){
            mention::where('comment_id', $current_comment->id)->delete();
        }
        Comment::where('tweet_id', $tweet->id)->delete();
        Like::where('tweet_id', $tweet->id)->delete();
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
        if(strpos($comment_body, "@") !== false ){
            $explode_at = explode("@", $comment_body ) ;
            $get_username = explode(" ", $explode_at[1] );
            if($get_username != ""){
                $mention_user = User::where('username' , $get_username)->first();
                if($mention_user){
                    $get_follow_user = Follow::where(['userId' => $user->id , 'Follow_userId' => $mention_user->id])->first();
                    if($get_follow_user){
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
            }
        }
        return redirect()->route('news-feed')->with(['message' => "Comment successfully Added!"]);
    }

    public function getEditComment(Comment $commentId)
    {
        if(Auth::user()->id != $commentId->user_id){
            return redirect()->back()->with(['message_err' => 'Please Update Your Comments Only!']);
        }
        return view('edit_comment')->with(['comment' => $commentId]);
    }

    public function PostEditComment(Request $request)
    {
        $this->validate($request, [
            'new-comment' => 'required|max:1000'
        ]);
        $comment_body = $request['new-comment'];
        $comment_id = $request['comment_id'];
        $comment = Comment::find($comment_id);
        if (!$comment) {
            return redirect()->back()->with(['message_err' => 'Comment Not Found!']);
        }
        if(Auth::user()->id != $comment->user_id){
            return redirect()->back();
        }
        $comment->comment = $comment_body;
        $comment->update();
        if(strpos($comment_body, "@") !== false ){
            $explode_at = explode("@", $comment_body ) ;
            $get_username = explode(" ", $explode_at[1] );
            if($get_username != ""){
                $get_mention_comment = mention::where('comment_id' , $comment->id)->first();
                if($get_mention_comment){
                    if($get_mention_comment->mention_username != $get_username){
                        $get_mention_comment->delete();
                    }
                }
                $mention_user = User::where('username' , $get_username)->first();
                $user = Auth::user();
                if($mention_user){
                    $get_follow_user = Follow::where(['userId' => $user->id , 'Follow_userId' => $mention_user->id])->first();
                    if($get_follow_user) {
                        $new_mention = new mention();
                        $new_mention->user_id = $user->id;
                        $new_mention->mention_user_id = $mention_user->id;
                        $new_mention->tweet_id = $comment->tweet_id;
                        $new_mention->comment_id = $comment->id;
                        $new_mention->mention_username = $mention_user->username;
                        $new_mention->save();
                    }
                }
            }
        }

        return redirect()->route('news-feed')->with(['message' => 'Your tweet Successfully Updated!']);
    }

    public function getDeleteComment($commentId)
    {
        $comment = Comment::where('id', $commentId)->first();
        if (Auth::user()->id != $comment->user_id) {
            return redirect()->back();
        }
        mention::where('comment_id', $commentId)->delete();
        $comment->delete();
        return redirect()->route('news-feed')->with(['message' => 'Comment successfully deleted!']);
    }
}
