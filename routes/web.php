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


Route::group(['prefix' => 'security', 'namespace' => 'Security', 'middleware' => ['auth']], function () {

    Route::get('updateUserIndex','UpdateUserCO@index');

    Route::get('changePasswordIndex','UpdateUserCO@index');

    Route::get('resetPasswordIndex','ChangePasswordCO@index');

    Route::get('managePermissionIndex','ManageUserPermissionCO@index');
    Route::get('usersData','ManageUserPermissionCO@usersDTData'); //for Data Table

    Route::get('getData/{id}','ManageUserPermissionCO@userData'); //for Specific user data

    Route::post('permission/store','ManageUserPermissionCO@store');
});

Route::group(['prefix' => 'projects', 'namespace' => 'Projects\Basic', 'middleware' => ['auth']], function () {

    Route::get('basic/newProjectIndex','NewProjectsCO@index');
    Route::get('basic/projectData','NewProjectsCO@getProjectData');
    Route::post('basic/newProjectSave','NewProjectsCO@store');

//    Route::get('account.group.data','AccountController@getGroupData');
//    Route::post('account.group.add','AccountController@addGroupData');
//    Route::any('account.group.update/{id}','AccountController@editGroupData');
//    Route::any('account.group.delete/{id}','AccountController@deleteGroupData');
});


