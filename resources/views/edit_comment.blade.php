@extends('layouts.master')

@section('title')
    edit comment
@endsection

@section('content')
    @include('includes.message-block')
    @if($comment)
        <section class="row new-post">
            <div class="col-md-6 col-md-offset-3">
                <h1>Edit Your Comment</h1>
                <form action="{{ route('post.edit.comment') }}" method="post">
                    <div class="form-group  {{ $errors->has('new-comment') ? 'has-error' : '' }}">
                    <textarea class="form-control" name="new-comment" id="new-comment" rows="5"
                              placeholder="Your comment">{{ $comment->comment }}</textarea>
                    </div>
                    <input type="hidden" value="{{ $comment->id }}" name="comment_id">
                    <button type="submit" class="btn btn-primary">Edit Comment</button>
                    <input type="hidden" name="_token" value="{{ Session::token() }}">
                </form>
            </div>
        </section>
    @endif
@endsection