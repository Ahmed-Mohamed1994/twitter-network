<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['web']], function () {
    Route::get('/', [
        'uses' => 'UserController@getHome',
        'as' => 'login'
    ]);

    Route::post('/signup', [
        'uses' => 'UserController@postSignUp',
        'as' => 'signup'
    ]);

    Route::post('/signin', [
        'uses' => 'UserController@postSignIn',
        'as' => 'signin'
    ]);

    Route::get('/logout', [
        'uses' => 'UserController@getLogout',
        'as' => 'logout'
    ]);

    Route::get('/account', [
        'uses' => 'UserController@getAccount',
        'as' => 'account',
        'middleware' => 'auth'
    ]);

    Route::post('/updateaccount',[
        'uses' => 'UserController@postSaveAccount',
        'as' => 'account.save',
        'middleware' => 'auth'
    ]);

    Route::get('/userimage/{filename}',[
        'uses' => 'UserController@getUserImage',
        'as' => 'account.image',
        'middleware' => 'auth'
    ]);

    Route::get('/dashboard', [
        'uses' => 'UserController@getDashboard',
        'as' => 'dashboard',
        'middleware' => 'auth'
    ]);

    Route::get('/news_feed', [
        'uses' => 'UserController@getNewsFeed',
        'as' => 'news-feed',
        'middleware' => 'auth'
    ]);

    Route::get('/activity_feed', [
        'uses' => 'UserController@getActivityFeed',
        'as' => 'activity-feed',
        'middleware' => 'auth'
    ]);

    Route::post('/search', [
        'uses' => 'UserController@getSearchUsername',
        'as' => 'search.username',
        'middleware' => 'auth'
    ]);

    Route::get('/account/{userId}', [
        'uses' => 'UserController@getUserAccount',
        'as' => 'user.account',
        'middleware' => 'auth'
    ]);

    Route::get('/follow/{userId}', [
        'uses' => 'UserController@getUserFollow',
        'as' => 'user.follow',
        'middleware' => 'auth'
    ]);

    Route::get('/unfollow/{userId}', [
        'uses' => 'UserController@getUserUnFollow',
        'as' => 'user.unfollow',
        'middleware' => 'auth'
    ]);
});
