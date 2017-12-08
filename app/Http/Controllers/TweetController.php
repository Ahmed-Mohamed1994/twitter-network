<?php

namespace App\Http\Controllers;

use App\Tweet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TweetController extends Controller
{
    public function postCreateTweet(Request $request){
        $this->validate($request, [
            'new-tweet' => 'required|max:1000'
        ]);
        $tweet = new Tweet();
        $tweet->body = $request['new-tweet'];
        $tweet->userId = Auth::user()->id;
        if($tweet->save()){
            return redirect()->route('dashboard')->with(['message' => 'Your Tweet Successfully Created!']);
        }
    }
}
