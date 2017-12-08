@extends('layouts.master')

@section('title')
    twitter dashboard
@endsection

@section('content')
    @include('includes.message-block')
    <section class="row new-post">
        <div class="col-md-6">
            <header><h3>What do you have to say?</h3></header>
            <form action="" method="post">
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
                    <input type="text" name="username" class="form-control" value="{{ Request::old('username') }}" id="username">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
                <input type="hidden" name="_token" value="{{ Session::token() }}">
            </form>
        </div>
    </section>

@endsection