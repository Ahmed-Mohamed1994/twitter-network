@extends('layouts.master')

@section('title')
    twitter
@endsection

@section('content')
    @if($search_users)
        <section class="row new-post">
            <div class="col-md-6 col-md-offset-3">
                <header><h3>User from your search</h3></header>
                @foreach($search_users as $user_search)
                    <article class="user-search-algolia">
                        <div class="col-md-6">
                            @if (Storage::disk('local')->has($user_search->username . '-' . $user_search->id . '.jpg'))
                                <img src="{{ route('account.image', ['filename' => $user_search->username . '-' . $user_search->id . '.jpg']) }}"
                                     alt="" class="img-responsive">
                            @endif
                        </div>
                        <div class="col-md-6">
                            <h3>Name : {{ $user_search->name }}</h3>
                        </div>
                        <div class="account-view-btn text-center">
                            <a class="btn btn-primary"
                               href="{{ route('user.account',['$userId' => $user_search->id]) }}">View
                                Account</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
@endsection