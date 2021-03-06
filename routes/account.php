<?php

Route::group(['prefix' => 'ledger', 'namespace' => 'Accounts\Ledger', 'middleware' => ['auth']], function () {

    Route::get('GLGroupIndex','GLGroupCo@index');
    Route::get('GLGroupData','GLGroupCo@getGLGroupData');
    Route::post('group/save','GLGroupCo@store');

    Route::delete('group/delete/{id}','GLGroupCo@destroy');
    Route::post('group/update/{id}','GLGroupCo@update');


    Route::get('GLAccountHeadIndex','GLAccountHeadCo@index');
    Route::get('GLAccountHeadData','GLAccountHeadCo@getGLAccountHeadData');

    Route::post('save','GLAccountHeadCo@store');
    Route::post('update/{id}','GLAccountHeadCo@update');
    Route::delete('head/delete/{id}','GLAccountHeadCo@destroy');

    Route::get('account/print','GLAccountHeadCo@print');

});


Route::group(['prefix' => 'ledger', 'namespace' => 'Accounts\Ledger', 'middleware' => ['auth']], function () {

    Route::get('openingBalanceIndex','OpeningBalanceCO@index');
    Route::get('openingPost','OpeningBalanceCO@store');

//    Route::get('GLGroupData','GLGroupCo@getGLGroupData');
//    Route::post('group/save','GLGroupCo@store');

    Route::get('depreciationSetupIndex','DepreciationSetupCO@index');
    Route::get('getDepreciationData','DepreciationSetupCO@getDepreciationData');
    Route::post('saveDepreciationAccount','DepreciationSetupCO@create');
    Route::post('depreciation/store','DepreciationSetupCO@store');

});


Route::group(['prefix' => 'setup', 'namespace' => 'Accounts\Setup', 'middleware' => ['auth']], function () {

    Route::get('bankInfoIndex','BankInformationCO@index');
    Route::get('getBanks','BankInformationCO@getBanks');
    Route::post('saveBankInfo','BankInformationCO@store');
    Route::post('update/{id}','BankInformationCO@update');
    Route::post('delete/{id}','BankInformationCO@destroy');

});

// Cost Center Routes

Route::group(['prefix' => 'costcenter', 'namespace' => 'Accounts\Costcenter', 'middleware' => ['auth']], function () {

    Route::get('costCenterIndex','CostCenterCO@index');
    Route::get('getCostCenterInfo','CostCenterCO@getdata');
    Route::post('saveNewCenterIndex','CostCenterCO@store');

    Route::get('rptCostCenterSummary','CostCenterCO@summary');
    Route::get('transactions/{id}/{month}','CostCenterCO@transactions');

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


    //JOURNAL TRANSACTIONS ROUTE
    Route::get('transJournalIndex','JournalTransactionCO@index');
    Route::post('journal/save','JournalTransactionCO@store');


    // Memorandum Voucher Route

    Route::get('transMemorandumIndex','MemorandumTransactionCO@index');
    Route::post('Memorandum/save','MemorandumTransactionCO@store');


//EDIR

    Route::get('editUnAuthVoucherIndex','EditGLVoucherCO@index');
    Route::post('updateUnAuthVoucherIndex','EditGLVoucherCO@update');

    //Authorise Transaction Routes

    Route::get('authoriseTransIndex','AuthoriseTransactionCO@index');
    Route::post('authoriseTransIndex','AuthoriseTransactionCO@update');

    Route::get('getUnAuthVoucherData','AuthoriseTransactionCO@getVoucherData');
    Route::get('authorise/{id}','AuthoriseTransactionCO@authorise');






});


Route::group(['prefix' => 'accounts/report', 'namespace' => 'Accounts\Report', 'middleware' => ['auth']], function () {

    //PAYMENT TRANSACTIONS ROUTE

    Route::get('dailyTransactionIndex','DailyTransactionReportCO@index');
    Route::get('printVoucherIndex','PrintVoucherControllerCO@index');


    Route::get('rptTrialBalanceIndex','RepTrialBalanceCO@index');

    Route::get('details/{id}/{date}','RepTrialBalanceCO@details');

    Route::get('previousTrialBalanceIndex','RepTrialBalanceCO@previousIndex');


    Route::get('rptGeneralLedgerIndex','RepGeneralLedgerCO@index');

    Route::get('previousGenLedgerIndex','RepGeneralLedgerCO@previousGenLedgerIndex');

    Route::get('ajax/{accNo}','RepGeneralLedgerCO@getAccount');

});



// BUDGET ROUTES

Route::group(['prefix' => 'budget', 'namespace' => 'Accounts\Budget', 'middleware' => ['auth']], function () {

    //PAYMENT TRANSACTIONS ROUTE

    Route::get('prepareBudgetIndex','PrepareBudgetCO@index');
    Route::get('getBudgetInfo','PrepareBudgetCO@getBudgetInfo');
    Route::post('prepareBudgetIndex','PrepareBudgetCO@store');

});




// BUDGET ROUTES

Route::group(['prefix' => 'rtgs', 'namespace' => 'Accounts\RTGS', 'middleware' => ['auth']], function () {

    //PAYMENT TRANSACTIONS ROUTE

    Route::get('rtgsPaymentIndex','RtgsPaymentFormCO@index');
    Route::get('rtgsPayment','RtgsPaymentFormCO@store');

});



// FINANCIAL STATEMENTS' ROUTES

Route::group(['prefix' => 'statement', 'namespace' => 'Accounts\Statement', 'middleware' => ['auth']], function () {

    //PAYMENT TRANSACTIONS ROUTE

    Route::get('createFileIndex','CreateStatementFileCO@index');
    Route::get('statementFileList','CreateStatementFileCO@getStatementFileList');
    Route::post('createFileIndex','CreateStatementFileCO@store');

    Route::post('updateFileIndex','CreateStatementFileCO@update');



    Route::get('lineStatementIndex','CreateStatementLineCO@index');
    Route::get('getStatementLineData/{id}','CreateStatementLineCO@getStatementLineData');
    Route::post('lineStatementIndex','CreateStatementLineCO@store');

    Route::post('updateStatementLineIndex','CreateStatementLineCO@update');
    Route::delete('lineDelete/{id}','CreateStatementLineCO@destroy');


    // Print Statement Routes

    Route::get('printStatementIndex','PrintStatementCO@index');
    Route::post('prepareStatementIndex','PrintStatementCO@prepare');
    Route::get('showStatementIndex','PrintStatementCO@show');

});
