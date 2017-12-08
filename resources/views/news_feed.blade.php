@extends('layouts.master')

@section('title')
    twitter dashboard
@endsection

@section('content')
    @include('includes.message-block')
    <h1>People you are following</h1>
    <div class="row">
        @foreach($following_users as $user)
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    @if (Storage::disk('local')->has($user->username . '-' . $user->id . '.jpg'))
                        <img src="{{ route('account.image', ['filename' => $user->username . '-' . $user->id . '.jpg']) }}"
                             alt="" class="image-news-feed">
                    @endif
                    <div class="caption">
                        <h3>{{ $user->username }}</h3>
                        <p class="text-center"><a href="{{ route('user.account',['$userId' => $user->id]) }}"
                                                  class="btn btn-primary" role="button">View Account</a></p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <hr>
    <section class="row tweets">
        <div class="col-md-6 col-md-offset-3">
            <header><h3>Your tweets</h3></header>
            @foreach($tweets as $tweet)
                <article class="tweet" data-TweetId="{{ $tweet->id }}">
                    <p>{{ $tweet->body }}</p>
                    <div class="info">
                        Posted by {{ Auth::user()->username }} on {{ $tweet->created_at->toFormaTtedDateString() }}
                    </div>
                    <div class="interaction">
                        <a href="">Like</a> |
                        @if(Auth::user()->id == $tweet->userId) |
                        <a href="{{ route('get.edit.tweet',['tweetId' => $tweet->id]) }}">Edit</a> |
                        <a href="">Delete</a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection