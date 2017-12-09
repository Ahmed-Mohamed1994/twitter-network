@extends('layouts.master')

@section('title')
    twitter News Feed
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
                <article class="tweet" data-tweetid="{{ $tweet->id }}">
                    <div class="media-left">
                        <a href="#">
                            @if (Storage::disk('local')->has(Auth::user()->username . '-' . Auth::user()->id . '.jpg'))
                                <img class="media-object image-post" src="{{ route('account.image', ['filename' => Auth::user()->username . '-' . Auth::user()->id . '.jpg']) }}" alt="" >
                            @endif
                        </a>
                    </div>
                    <div class="media-right">
                        <p>{{ $tweet->body }}</p>
                        <div class="info">
                            Posted by {{ Auth::user()->username }} on {{ $tweet->created_at->toFormaTtedDateString() }}
                        </div>
                        <div class="interaction">
                            <a href="#" class="like">{{ Auth::user()->likes()->where('tweet_id', $tweet->id)->first() ? 'DisLike' : 'Like' }}</a> |
                            @if(Auth::user()->id == $tweet->userId)
                                <a href="{{ route('get.edit.tweet',['tweetId' => $tweet->id]) }}">Edit</a> |
                                <a href="{{ route('get.delete.tweet',['tweetId' => $tweet->id]) }}">Delete</a>
                            @endif
                        </div>
                    </div>

                    @foreach($comments as $comment)
                        @if($tweet->id == $comment->tweet_id)
                            <div class="media">
                                <div class="comment" data-commentid="{{ $comment->id }}">
                                    <div class="media-left">
                                        <a href="#">
                                            @if (Storage::disk('local')->has($comment->user->username . '-' . $comment->user->id . '.jpg'))
                                                <img class="media-object image-comment" src="{{ route('account.image', ['filename' => $comment->user->username . '-' . $comment->user->id . '.jpg']) }}" alt="" >
                                            @endif
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <strong>{{ $comment->user->username }}</strong><p>{{ $comment->comment }}</p>
                                        <p class="comment_time">Created at {{ $comment->created_at }} <span class="glyphicon glyphicon-time"></span></p>
                                        <div class="interaction">

                                            @if($comment->user == Auth::user())
                                                <a href="#">Edit</a> |
                                                <a href="">Delete</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <form action="{{ route('comment.add') }}" method="post" class="form_comment">
                        <div class="form-group {{ $errors->has('tweet_comment') ? 'has-error' : '' }}">
                            <textarea class="form-control" name="tweet_comment" id="tweet_comment" rows="2" placeholder="Your Comment"></textarea>
                            <input type="hidden" name="tweet_comment_id" value="{{ $tweet->id }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Add Comment</button>
                        {{ csrf_field() }}
                    </form>
                </article>
                <hr>
            @endforeach
        </div>
    </section>

    <script>
        var token = '{{ Session::token() }}';
        var urlLike = '{{ route('like') }}';
    </script>
@endsection