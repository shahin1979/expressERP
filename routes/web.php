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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['prefix' => 'company', 'namespace' => 'Company', 'middleware' => ['auth']], function () {

    Route::get('fiscalPeriodIndex','FiscalPeriodCO@index');
    Route::get('fiscalData','FiscalPeriodCO@getFiscalData');

    Route::get('basicIndex','CompanyPropertiesCO@index');
    Route::post('propertiesSave','CompanyPropertiesCO@store');

//    Route::get('account.group.data','AccountController@getGroupData');
//    Route::post('account.group.add','AccountController@addGroupData');
//    Route::any('account.group.update/{id}','AccountController@editGroupData');
//    Route::any('account.group.delete/{id}','AccountController@deleteGroupData');
});


