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


Route::group(['prefix' => 'product', 'namespace' => 'Inventory\Product', 'middleware' => ['auth']], function () {

    Route::get('itemBrandIndex','ItemBrandCO@index');
    Route::post('itemBrandIndex','ItemBrandCO@store');

    Route::get('getBrandDBData','ItemBrandCO@getBrandDBData');

    Route::post('itemBrand/update/{id}','ItemBrandCO@update');
    Route::delete('itemBrand/delete/{id}','ItemBrandCO@destroy');

});


Route::group(['prefix' => 'product', 'namespace' => 'Inventory\Product', 'middleware' => ['auth']], function () {

    Route::get('itemSizeIndex','ItemSizeCO@index');
    Route::post('itemSizeIndex','ItemSizeCO@store');

    Route::get('getSizeDBData','ItemSizeCO@getSizeDBData');

    Route::post('itemSize/update/{id}','ItemSizeCO@update');
    Route::delete('itemSize/delete/{id}','ItemSizeCO@destroy');

});


Route::group(['prefix' => 'product', 'namespace' => 'Inventory\Product', 'middleware' => ['auth']], function () {

    Route::get('itemColorIndex','ItemColorCO@index');
    Route::post('itemColorIndex','ItemColorCO@store');

    Route::get('getColorDBData','ItemColorCO@getColorDBData');

    Route::post('itemColor/update/{id}','ItemColorCO@update');
    Route::delete('itemColor/delete/{id}','ItemColorCO@destroy');

});


Route::group(['prefix' => 'product', 'namespace' => 'Inventory\Product', 'middleware' => ['auth']], function () {

    Route::get('itemModelIndex','ItemModelCO@index');
    Route::post('itemModelIndex','ItemModelCO@store');

    Route::get('getModelDBData','ItemModelCO@getModelDBData');

    Route::post('itemModel/update/{id}','ItemModelCO@update');
    Route::delete('itemModel/delete/{id}','ItemModelCO@destroy');

});


Route::group(['prefix' => 'product', 'namespace' => 'Inventory\Product', 'middleware' => ['auth']], function () {

    Route::get('itemGodownIndex','ItemGodownCO@index');
    Route::post('itemGodownIndex','ItemGodownCO@store');

    Route::get('getGodownDBData','ItemGodownCO@getGodownDBData');

    Route::post('itemGodown/update/{id}','ItemGodownCO@update');
    Route::delete('itemGodown/delete/{id}','ItemGodownCO@destroy');

});

Route::group(['prefix' => 'product', 'namespace' => 'Inventory\Product', 'middleware' => ['auth']], function () {

    Route::get('itemRackIndex','ItemRackCO@index');
    Route::post('itemRackIndex','ItemRackCO@store');

    Route::get('getRackDBData','ItemRackCO@getGodownDBData');

    Route::post('itemRack/update/{id}','ItemRackCO@update');
    Route::delete('itemRack/delete/{id}','ItemRackCO@destroy');

});


Route::group(['prefix' => 'product', 'namespace' => 'Inventory\Product', 'middleware' => ['auth']], function () {

    Route::get('itemTaxIndex','ItemTaxesCO@index');
    Route::post('itemTaxIndex','ItemTaxesCO@store');

    Route::get('getTaxDBData','ItemTaxesCO@getTaxesDBData');

    Route::post('itemTax/update/{id}','ItemTaxesCO@update');
    Route::delete('itemTax/delete/{id}','ItemTaxesCO@destroy');

});


Route::group(['prefix' => 'product', 'namespace' => 'Inventory\Product', 'middleware' => ['auth']], function () {

    Route::get('productIndex','ProductsCO@index');
    Route::post('productIndex','ProductsCO@store');

    Route::get('itemSKU','ProductsCO@getSKU');

    Route::get('getProductDBData','ProductsCO@getProductsDBData');

    Route::post('products/update/{id}','ProductsCO@update');
    Route::delete('products/delete/{id}','ProductsCO@destroy');


});
