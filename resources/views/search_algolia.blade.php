@extends('layouts.master')

@section('title')
    twitter dashboard
@endsection

@section('content')
    @include('includes.message-block')
    <section class="row new-post">
        <div class="col-md-6 col-md-offset-3">
            <header><h3>Enter Search Key to Search?</h3></header>
            <form action="{{ route('post.search.algolia') }}" method="post">
                <div class="form-group  {{ $errors->has('searchKey') ? 'has-error' : '' }}">
                    <input type="text" name="searchKey" class="form-control" value="{{ Request::old('searchKey') }}"
                           id="searchKey">
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
                <input type="hidden" name="_token" value="{{ Session::token() }}">
            </form>
        </div>
    </section>


@endsection