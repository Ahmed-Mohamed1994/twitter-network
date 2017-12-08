@extends('layouts.master')

@section('title')
    User Account
@endsection

@section('content')
    @include('includes.message-block')
    <section class="row new-post">
        <div class="col-md-6 col-md-offset-3">
            <header><h3>Your Account</h3></header>
            <form action="{{ route('account.save') }}" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="username">User Name</label>
                    <input type="text" name="username" class="form-control" value="{{ $user->username }}" id="username">
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" id="name">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" class="form-control" value="{{ $user->email }}" id="email">
                </div>
                <div class="form-group {{ $errors->has('old_password') ? 'has-error' : '' }}">
                    <label for="old_password">Your Current Password</label>
                    <input class="form-control" type="password" name="old_password" id="old_password">
                </div>
                <div class="form-group {{ $errors->has('new_password') ? 'has-error' : '' }}">
                    <label for="new_password">Your New Password</label>
                    <input class="form-control" type="password" name="new_password" id="new_password">
                </div>
                <div class="form-group">
                    <label for="image">Image (only .jpg)</label>
                    <input type="file" name="image" class="form-control" id="image">
                </div>
                <button type="submit" class="btn btn-primary">Save Account</button>
                <input type="hidden" value="{{ Session::token() }}" name="_token">
            </form>
        </div>
    </section>
    @if (Storage::disk('local')->has($user->username . '-' . $user->id . '.jpg'))
        <section class="row new-profile-image">
            <div class="col-md-6 col-md-offset-3">
                <img src="{{ route('account.image', ['filename' => $user->username . '-' . $user->id . '.jpg']) }}" alt="" class="img-responsive">
            </div>
        </section>
    @endif
@endsection