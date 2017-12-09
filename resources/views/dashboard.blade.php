@extends('layouts.master')

@section('title')
    twitter dashboard
@endsection

@section('content')
    @include('includes.message-block')
    <section class="row new-post">
        <div class="col-md-6">
            <header><h3>What do you have to say?</h3></header>
            <form action="{{ route('create.tweet') }}" method="post">
                <div class="form-group  {{ $errors->has('new-tweet') ? 'has-error' : '' }}">
                    <textarea class="form-control" name="new-tweet" id="new-tweet" rows="5"
                              placeholder="Your tweet">{{ Request::old('new-tweet') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Create Tweet</button>
                <input type="hidden" name="_token" value="{{ Session::token() }}">
            </form>
        </div>
        <div class="col-md-6">
            <header><h3>Enter username to Search?</h3></header>
            <form action="{{ route('search.username') }}" method="post">
                <div class="form-group  {{ $errors->has('username') ? 'has-error' : '' }}">
                    <input type="text" name="username" class="form-control" value="{{ Request::old('username') }}"
                           id="username">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
                <input type="hidden" name="_token" value="{{ Session::token() }}">
            </form>
        </div>
    </section>

    <hr>
    <section class="row tweets">
        <div class="col-md-6 col-md-offset-3">
            <header><h3>Tweets of Following Users</h3></header>
            @if($tweets_of_following_users)
                @foreach($tweets_of_following_users as $tweets)
                    @foreach($tweets as $tweet)
                        <article class="tweet" data-tweetid="{{ $tweet->id }}">
                            <div class="media-left">
                                <a href="#">
                                    @foreach($following_users as $current_tweet_user)
                                        @if($current_tweet_user->id == $tweet->userId)
                                            @if (Storage::disk('local')->has($current_tweet_user->username . '-' . $current_tweet_user->id . '.jpg'))
                                                <img class="media-object image-post"
                                                     src="{{ route('account.image', ['filename' => $current_tweet_user->username . '-' . $current_tweet_user->id . '.jpg']) }}"
                                                     alt="">
                                            @endif
                                        @endif
                                    @endforeach
                                </a>
                            </div>
                            <div class="media-right">
                                <p>{{ $tweet->body }}</p>
                                <div class="info">
                                    Posted by
                                    @foreach($following_users as $current_tweet_user)
                                        @if($current_tweet_user->id == $tweet->userId)
                                            {{ $current_tweet_user->username }}
                                        @endif
                                    @endforeach
                                    on {{ $tweet->created_at->toFormaTtedDateString() }}
                                </div>
                                <div class="interaction">
                                    <a href="#"
                                       class="like">{{ Auth::user()->likes()->where('tweet_id', $tweet->id)->first() ? 'DisLike' : 'Like' }}</a>
                                    @if(Auth::user()->id == $tweet->userId)
                                        | <a href="{{ route('get.edit.tweet',['tweetId' => $tweet->id]) }}">Edit</a> |
                                        <a href="{{ route('get.delete.tweet',['tweetId' => $tweet->id]) }}">Delete</a>
                                    @endif
                                </div>
                            </div>

                            @foreach($comments as $comment)
                                @if($tweet->id == $comment->tweet_id)
                                    <div class="media">
                                        <div class="comment" data-commentid="{{ $comment->id }}">
                                            <div class="media-left">
                                                @if (Storage::disk('local')->has($comment->user->username . '-' . $comment->user->id . '.jpg'))
                                                    <img class="media-object image-comment"
                                                         src="{{ route('account.image', ['filename' => $comment->user->username . '-' . $comment->user->id . '.jpg']) }}"
                                                         alt="">
                                                @endif
                                            </div>
                                            <div class="media-body">
                                                <strong>{{ $comment->user->username }}</strong>
                                                <p>{{ $comment->comment }}</p>
                                                <p class="comment_time">Created at {{ $comment->created_at }} <span
                                                            class="glyphicon glyphicon-time"></span></p>
                                                <div class="interaction">

                                                    @if($comment->user == Auth::user())
                                                        <a href="{{ route('get.edit.comment',['commentId' => $comment->id]) }}">Edit</a>
                                                        |
                                                        <a href="{{ route('get.delete.comment',['commentId' => $comment->id]) }}">Delete</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <form action="{{ route('comment.add') }}" method="post" class="form_comment">
                                <div class="form-group {{ $errors->has('tweet_comment') ? 'has-error' : '' }}">
                                <textarea class="form-control" name="tweet_comment" id="tweet_comment" rows="2"
                                          placeholder="Your Comment"></textarea>
                                    <input type="hidden" name="tweet_comment_id" value="{{ $tweet->id }}">
                                </div>
                                <button type="submit" class="btn btn-primary">Add Comment</button>
                                {{ csrf_field() }}
                            </form>
                        </article>
                        <hr>
                    @endforeach
                @endforeach
            @endif
        </div>
    </section>

    <script>
        var token = '{{ Session::token() }}';
        var urlLike = '{{ route('like') }}';
    </script>

@endsection