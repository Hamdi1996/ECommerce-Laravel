<?php

use Illuminate\Support\Facades\Route;

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
define('PAGINATION_COUNT',10);
Route::group(['namespace'=>'Admin','middleware'=>'auth:admin'],function(){
    Route::get('/','DashboardController@index')->name('admin.dashboard');
    
    // Langaues Routes
    Route::group(['prefix'=>'languages'],function(){
        Route::get('/','LanguagesController@index')->name('admin.languages');
         // Create Language
        Route::get('/create','LanguagesController@create')->name('admin.languages.create');
        Route::post('/save','LanguagesController@store')->name('admin.languages.store');
          //Edit Language
        Route::get('/edit/{id}','LanguagesController@edit')->name('admin.languages.edit');
        Route::post('/update/{id}','LanguagesController@update')->name('admin.languages.update');
        Route::post('/save','LanguagesController@store')->name('admin.languages.store');
          //Delete Language
        Route::get('/delete/{id}','LanguagesController@destroy')->name('admin.languages.delete');
          /**************End Of Language Route************ */ 
        });

    // Main Category Routes
    Route::group(['prefix'=>'main_categories'],function(){
        Route::get('/','MainCategoriesController@index')->name('admin.maincategories');
         // Create Category
        Route::get('/create','MainCategoriesController@create')->name('admin.maincategories.create');
        Route::post('/save','MainCategoriesController@store')->name('admin.maincategories.store');
          //Edit Category
        Route::get('/edit/{id}','MainCategoriesController@edit')->name('admin.maincategories.edit');
        Route::post('/update/{id}','MainCategoriesController@update')->name('admin.maincategories.update');
        Route::post('/save','MainCategoriesController@store')->name('admin.maincategories.store');
          //Delete Category
        Route::get('/delete/{id}','MainCategoriesController@destroy')->name('admin.maincategories.delete');
          /**************End Of Category Route************ */  
        });

        // Vendors Routes
    Route::group(['prefix'=>'vendors'],function(){
        Route::get('/','VendorsController@index')->name('admin.vendors');
         // Create Category
        Route::get('/create','VendorsController@create')->name('admin.vendors.create');
        Route::post('/save','VendorsController@store')->name('admin.vendors.store');
          //Edit Category
        Route::get('/edit/{id}','VendorsController@edit')->name('admin.vendors.edit');
        Route::post('/update/{id}','VendorsController@update')->name('admin.vendors.update');
        Route::post('/save','VendorsController@store')->name('admin.vendors.store');
          //Delete Category
        Route::get('/delete/{id}','VendorsController@destroy')->name('admin.vendors.delete');
          /**************End Of Category Route************ */  
        });
});



Route::group(['namespace'=>'Admin','middleware'=>'guest:admin'],function(){

    Route::get('login','LoginController@getLogin')->name('get.admin.login');
    Route::post('login','LoginController@login')->name('admin.login');
});
