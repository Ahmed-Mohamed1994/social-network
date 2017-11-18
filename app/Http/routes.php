<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


Route::group(['middleware' => ['web']], function(){
    Route::get('/',[
        'uses' => 'UserController@getHome',
        'as' => 'home'
    ]);
    Route::post('/signup',[
        'uses' => 'UserController@postSignUp',
        'as' => 'signup'
    ]);
    Route::post('/signin',[
        'uses' => 'UserController@postSignIn',
        'as' => 'signin'
    ]);

    Route::get('/logout',[
        'uses' => 'UserController@getLogout',
        'as' => 'logout',
        'middleware' => 'auth'
    ]);

    Route::get('/account',[
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
        'uses' => 'PostController@getDashboard',
        'as' => 'dashboard',
        'middleware' => 'auth'
    ]);

    Route::post('/createpost',[
        'uses' => 'PostController@postCreatePost',
        'as' => 'post.create',
        'middleware' => 'auth'
    ]);

    Route::get('/delete-post/{post_id}',[
        'uses' => 'PostController@getDeletePost',
        'as' => 'post.delete',
        'middleware' => 'auth'
    ]);

    Route::post('/edit', [
        'uses' => 'PostController@postEditPost',
        'as' => 'edit',
        'middleware' => 'auth'
    ]);

    Route::post('/like', [
        'uses' => 'PostController@postLikePost',
        'as' => 'like',
        'middleware' => 'auth'
    ]);

    Route::post('/add-comment', [
        'uses' => 'PostController@postAddComment',
        'as' => 'comment.add',
        'middleware' => 'auth'
    ]);

    Route::get('/delete-comment/{comment_id}',[
        'uses' => 'PostController@getDeleteComment',
        'as' => 'comment.delete',
        'middleware' => 'auth'
    ]);

    Route::post('/edit-comment', [
        'uses' => 'PostController@postEditComment',
        'as' => 'edit.comment',
        'middleware' => 'auth'
    ]);

    Route::post('/like-comment', [
        'uses' => 'PostController@postLikeComment',
        'as' => 'like.comment',
        'middleware' => 'auth'
    ]);

    Route::get('/single-post/{post_id}', [
        'uses' => 'PostController@getSinglePost',
        'as' => 'single.post',
        'middleware' => 'auth'
    ]);
});