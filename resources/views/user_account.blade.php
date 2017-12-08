@extends('layouts.master')

@section('title')
    twitter account
@endsection

@section('content')
    @include('includes.message-block')
    @if($user_account)
        <section class="row new-post">
            <div class="col-md-6 col-md-offset-3">
                <header><h2>{{ $user_account->name }}</h2></header>
                    @if (Storage::disk('local')->has($user_account->username . '-' . $user_account->id . '.jpg'))
                        <img src="{{ route('account.image', ['filename' => $user_account->username . '-' . $user_account->id . '.jpg']) }}" alt="" class="img-responsive">
                    @endif
                    <h3>Username : {{ $user_account->username }}</h3>
                @if($followed_this_user)
                    <div class="account-view-btn text-center">
                        <a class="btn btn-primary" href="{{ route('user.unfollow',['$userId' => $user_account->id]) }}">Unfollow</a>
                    </div>
                @else
                    <div class="account-view-btn text-center">
                        <a class="btn btn-primary" href="{{ route('user.follow',['$userId' => $user_account->id]) }}">Follow</a>
                    </div>
                @endif
            </div>
        </section>
    @endif
@endsection