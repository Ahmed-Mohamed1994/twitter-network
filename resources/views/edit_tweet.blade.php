@extends('layouts.master')

@section('title')
    edit tweet
@endsection

@section('content')
    @include('includes.message-block')
    @if($tweet)
        <section class="row new-post">
            <div class="col-md-6 col-md-offset-3">
                <h1>Edit Your Tweet</h1>
                <form action="{{ route('post.edit.tweet') }}" method="post">
                    <div class="form-group  {{ $errors->has('new-tweet') ? 'has-error' : '' }}">
                    <textarea class="form-control" name="new-tweet" id="new-tweet" rows="5"
                              placeholder="Your tweet">{{ $tweet->body }}</textarea>
                    </div>
                    <input type="hidden" value="{{ $tweet->id }}" name="tweet_id">
                    <button type="submit" class="btn btn-primary">Edit Tweet</button>
                    <input type="hidden" name="_token" value="{{ Session::token() }}">
                </form>
            </div>
        </section>
    @endif
@endsection