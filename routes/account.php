<?php

Route::group(['prefix' => 'ledger', 'namespace' => 'Accounts\Ledger', 'middleware' => ['auth']], function () {

    Route::get('GLGroupIndex','GLGroupCo@index');
    Route::get('GLGroupData','GLGroupCo@getGLGroupData');

    Route::get('GLAccountHeadIndex','GLAccountHeadCo@index');
    Route::get('GLAccountHeadData','GLAccountHeadCo@getGLAccountHeadData');

    Route::post('save','GLAccountHeadCo@store');

});


Route::group(['prefix' => 'transaction', 'namespace' => 'Accounts\Trans', 'middleware' => ['auth']], function () {

    //PAYMENT TRANSACTIONS ROUTE

    Route::get('transPaymentIndex','PaymentTransactionsCO@index');
    Route::post('transPaymentSave','PaymentTransactionsCO@store');
    Route::get('debit/head','PaymentTransactionsCO@getDebitHead');
    Route::get('creditBalance','PaymentTransactionsCO@getCreditBalance');
    Route::post('payment/save','PaymentTransactionsCO@store');


    //RECEIVE TRANSACTIONS ROUTE

    Route::get('transReceiveIndex','ReceiveTransactionCO@index');
    Route::post('transPaymentSave','ReceiveTransactionCO@store');
    Route::get('credit/head','ReceiveTransactionCO@getCreditHead');
    Route::get('debitBalance','ReceiveTransactionCO@getDebitBalance');
    Route::post('receive/save','ReceiveTransactionCO@store');




    Route::get('editUnAuthVoucherIndex','EditGLVoucherCO@index');
    Route::post('updateUnAuthVoucherIndex','EditGLVoucherCO@update');

});
