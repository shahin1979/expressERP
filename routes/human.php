<?php

Route::group(['prefix' => 'employee', 'namespace' => 'Human\Employee', 'middleware' => ['auth']], function () {

    Route::get('empPersonalIndex','EmployeePersonalCO@index');
    Route::post('personal/save','EmployeePersonalCO@store');

    Route::get('employeeDataTable','EmployeePersonalCO@getDTData');
    Route::post('image/upload','EmployeePersonalCO@uploadImage');

    Route::get('GLAccountHeadData','GLAccountHeadCo@getGLAccountHeadData');

    Route::post('save','GLAccountHeadCo@store');

});
