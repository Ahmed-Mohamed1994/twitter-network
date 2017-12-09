@extends('layouts.master')

@section('title')
    twitter activity feed
@endsection

@section('content')
    @include('includes.message-block')
    <section class="row new-post">
        <div class="col-md-6 col-md-offset-3">
            <h1>Your Likes Tweets</h1>
            @foreach($likes as $like)
                <article class="tweet text-center likes-activity">
                    You liked <h3>{{ $like->tweet->body }}</h3>
                    <div class="account-view-btn">
                        <a class="btn btn-primary" href="{{ route('get.tweet', ['tweetId' => $like->tweet_id]) }}">View Tweet</a>
                    </div>
                </article>
                <hr>
            @endforeach
        </div>
    </section>
    <section class="row new-post">
        <div class="col-md-6 col-md-offset-3">
            <h1>Your Mentions</h1>
            @foreach($mentions as $mention)
                <article class="tweet text-center mentions-activity">
                    <h3>{{ $mention->user->username }}</h3> mention You in his comment
                    <div class="account-view-btn">
                        <a class="btn btn-primary" href="{{ route('get.tweet', ['tweetId' => $mention->tweet_id]) }}">View Tweet</a>
                    </div>
                </article>
                <hr>
            @endforeach
        </div>
    </section>
@endsection