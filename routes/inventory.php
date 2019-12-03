<?php

Route::group(['prefix' => 'product', 'namespace' => 'Inventory\Product', 'middleware' => ['auth']], function () {

    Route::get('categoryIndex','CategoryCO@index');
    Route::post('categoryIndex','CategoryCO@store');

    Route::get('getCategoryData','CategoryCO@getCategoryData');

    Route::post('category/update/{id}','CategoryCO@update');

});
