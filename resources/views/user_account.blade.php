@extends('layouts.master')

@section('title')
    twitter account
@endsection

@section('content')
    @if($user_account)
        <section class="row new-post">
            <div class="col-md-6 col-md-offset-3">
                <header><h2>{{ $user_account->name }}</h2></header>
                    @if (Storage::disk('local')->has($user_account->username . '-' . $user_account->id . '.jpg'))
                        <img src="{{ route('account.image', ['filename' => $user_account->username . '-' . $user_account->id . '.jpg']) }}" alt="" class="img-responsive">
                    @endif
                    <h3>Username : {{ $user_account->username }}</h3>

            </div>
        </section>
    @endif
@endsection