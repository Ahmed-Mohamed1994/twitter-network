<?php

namespace App\Http\Controllers;

use App\Tweet;
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
}
