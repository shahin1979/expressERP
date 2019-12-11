<?php

Route::group(['prefix' => 'product', 'namespace' => 'Inventory\Product', 'middleware' => ['auth']], function () {

    Route::get('categoryIndex','CategoryCO@index');
    Route::post('categoryIndex','CategoryCO@store');

    Route::get('getCategoryData','CategoryCO@getCategoryData');

    Route::post('category/update/{id}','CategoryCO@update');
    Route::delete('category/delete/{id}','CategoryCO@destroy');

});


Route::group(['prefix' => 'product', 'namespace' => 'Inventory\Product', 'middleware' => ['auth']], function () {

    Route::get('subCategoryIndex','SubCategoryCO@index');
    Route::post('subCategoryIndex','SubCategoryCO@store');

    Route::get('getSubCategoryData','SubCategoryCO@getSubCategoryData');

    Route::post('subCategory/update/{id}','SubCategoryCO@update');
    Route::delete('subCategory/delete/{id}','SubCategoryCO@destroy');

});


Route::group(['prefix' => 'product', 'namespace' => 'Inventory\Product', 'middleware' => ['auth']], function () {

    Route::get('itemUnitIndex','ItemUnitCO@index');
    Route::post('itemUnitIndex','ItemUnitCO@store');

    Route::get('getUnitDBData','ItemUnitCO@getUnitDBData');

    Route::post('itemUnit/update/{id}','ItemUnitCO@update');
    Route::delete('itemUnit/delete/{id}','ItemUnitCO@destroy');

});
