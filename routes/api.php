<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api'], function () {
    Route::get('/articles', 'ArticleController@getArticles');
    Route::get('/articles/{id}', 'ArticleController@getArticle');
    Route::get('/categories', 'CategoryController@getCategories');
    Route::get('/authors', 'AuthorController@getAuthors');


});

Route::group(['namespace' => 'Auth'], function () {
    Route::post('/login', 'LoginController@login');
    Route::post('/register', 'RegisterController@register');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['namespace' => 'Api'], function () {
        Route::post('/preferences', 'UserPreferenceController@savePreferences');
    });
});
