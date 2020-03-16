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

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/dashboard', 'HomeController@index')->name('dashboard');

/**Users*/
Route::prefix('users')->group(function () {
    Route::get('/profile', 'UserMetasController@show')->name('user.profile-show');
    Route::get('/profile/edit', 'UserMetasController@edit')->name('user.edit');
    Route::put('/profile/{id}/update', 'UserMetasController@update')->name('user.update');
});

/**Menu*/
Route::prefix('menu')->group(function(){
    Route::get('/', 'JscMenusController@index')->name('menu.index');
    Route::post('/store', 'JscMenusController@store')->name('menu.store');
    Route::get('/{id}/menu_edit', 'JscMenusController@edit')->name('menu.edit');
    Route::put('/{id}', 'JscMenusController@update')->name('menu.update');
    // Route::put('/{id}/um_title', 'JscMenusController@update_title')->name('menu.update_title');//Update Menu Title
    Route::post('/menu_edit/nestable', 'JscMenusController@nestable')->name('menu.nestable');
    Route::delete('/destroy/{id}', 'JscMenusController@destroy')->name('menu.destroy'); 
});

/**Media*/
Route::prefix('media')->group(function(){
    Route::get('/', 'JscMediaController@index')->name('media.index');
    Route::post('/store', 'JscMediaController@store')->name('media.store');
    Route::put('/{id}', 'JscMediaController@update')->name('media.update');
    Route::get('/{id}/edit', 'JscMediaController@edit')->name('media.edit');
    Route::delete('/{id}/delete', 'JscMediaController@destroy')->name('media.destroy');
    Route::delete('/multipleDelete', 'JscMediaController@multipleDelete')->name('media.multiple');
    Route::get('/search','JscMediaController@searchMedia')->name('media.search');
    Route::get( '/getPagination', 'JscMediaController@paginations' )->name('media.paginations');
    Route::post('/getFilter', 'JscMediaController@filter')->name('media.filter');

    /**Upload */
    Route::get('/media-new', 'JscMediaController@upload')->name('media.upload');
});

/**Page&post*/
Route::prefix('page')->group(function(){
    Route::get('/', 'JscPostsController@page_index')->name('page.page_index');
    Route::get('/page-new', 'JscPostsController@page_create')->name('page.page_create');
    Route::post('/load-details/{id}', 'JscPostsController@load_details')->name('page.load_details');
    Route::post('/store','JscPostsController@store')->name('page.store');
    Route::get('/{id}/edit','JscPostsController@edit')->name('page.edit');
    Route::put('/{id}', 'JscPostsController@update')->name('page.update');
    Route::get('/search', 'JscPostsController@searchPage')->name('page.search');
    Route::post('/getFilter', 'JscPostsController@filter')->name('page.filter');
    Route::put('/movetotrash/{actionstats}', 'JscPostsController@trash')->name('page.trash');
    Route::put('/restore/{actionstats}', 'JscPostsController@restore')->name('page.restore');
    Route::delete('/destroy/{id}', 'JscPostsController@destroy')->name('page.destroy');
    
});