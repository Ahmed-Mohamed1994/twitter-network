@extends('layouts.master')

@section('title')
    tweet
@endsection

@section('content')
    <section class="row tweets">
        <div class="col-md-6 col-md-offset-3">
            <header><h3>Your tweets</h3></header>
            @if($tweet)
                <article class="tweet" data-tweetid="{{ $tweet->id }}">
                    <div class="media-left">
                        <a href="#">
                            @if (Storage::disk('local')->has($user_post->username . '-' . $user_post->id . '.jpg'))
                                <img class="media-object image-post"
                                     src="{{ route('account.image', ['filename' => $user_post->username . '-' . $user_post->id . '.jpg']) }}"
                                     alt="">
                            @endif
                        </a>
                    </div>
                    <div class="media-right">
                        <p>{{ $tweet->body }}</p>
                        <div class="info">
                            Posted by {{ $user_post->username }}
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
                        <div class="media">
                            <div class="comment" data-commentid="{{ $comment->id }}">
                                <div class="media-left">
                                    <a href="#">
                                        @if (Storage::disk('local')->has($comment->user->username . '-' . $comment->user->id . '.jpg'))
                                            <img class="media-object image-comment"
                                                 src="{{ route('account.image', ['filename' => $comment->user->username . '-' . $comment->user->id . '.jpg']) }}"
                                                 alt="">
                                        @endif
                                    </a>
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
            @endif
        </div>
    </section>

    <script>
        var token = '{{ Session::token() }}';
        var urlLike = '{{ route('like') }}';
    </script>
@endsection