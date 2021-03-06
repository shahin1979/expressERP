<?php

use Illuminate\Support\Facades\Route;

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
    Route::delete('brand/delete/{id}','ItemBrandCO@destroy');

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

    Route::get('getRackDBData','ItemRackCO@getRackDBData');

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

    Route::get('category/sub','ProductsCO@getSub');

    Route::get('itemSKU','ProductsCO@getSKU');

    Route::get('getProductDBData','ProductsCO@getProductsDBData');

    Route::post('item/update/{id}','ProductsCO@update');
    Route::delete('item/delete/{id}','ProductsCO@destroy');

    Route::get('rptProductLedgerIndex','Report\ProductLedgerCO@index');
    Route::get('productList','Report\ProductLedgerCO@autocomplete');




});

// REQUISITION ROUTES


Route::group(['prefix' => 'requisition', 'namespace' => 'Inventory\Requisition', 'middleware' => ['auth']], function () {

    Route::get('createReqIndex','CreateRequisitionCO@index');
    Route::post('createReqIndex','CreateRequisitionCO@store');
    Route::get('productList','CreateRequisitionCO@autocomplete');


    Route::get('editReqIndex','EditRequisitionCO@index');
    Route::get('requisitionData','EditRequisitionCO@getReqData');
    Route::get('edit/{id}','EditRequisitionCO@edit');
    Route::post('updateRequisition','EditRequisitionCO@update');

    Route::get('approveReqIndex','ApproveRequisitionCO@index');
    Route::get('reqDataForApprove','ApproveRequisitionCO@getReqData');
    Route::post('approve/{id}','ApproveRequisitionCO@approve');
    Route::post('reject/{id}','ApproveRequisitionCO@reject');

    Route::get('printReqIndex','PrintRequisitionCO@index');
//
    Route::get('reqDataForPrint','PrintRequisitionCO@getPrintReqData');
//
    Route::get('print/{id}','PrintRequisitionCO@print');
//    Route::delete('products/delete/{id}','ProductsCO@destroy');


});



// PURCHASE ROUTES


Route::group(['prefix' => 'purchase', 'namespace' => 'Inventory\Purchase', 'middleware' => ['auth']], function () {

    Route::get('supplierInfoIndex','SupplierInfoCO@index');
    Route::get('getSupplierInfo','SupplierInfoCO@getSupplierData');
    Route::post('supplierInfoIndex','SupplierInfoCO@store');

    Route::get('purchaseProductIndex','PurchaseProductCO@index'); //Direct Purchase
    Route::post('direct/save','PurchaseProductCO@store');

    Route::post('saveProductsUniqueId/{id}','PurchaseProductCO@uniqueID');


    Route::get('productList','PurchaseProductCO@autocomplete');

// Purchase Against Requisition

    Route::get('purchaseRequisitionIndex','RequisitionPurchaseCO@index');
//
//
    Route::get('reqDataForPurchase','RequisitionPurchaseCO@getReqPurchaseData');
    Route::get('purchaseIndex/{id}','RequisitionPurchaseCO@purchase');

    Route::post('itemSummary','RequisitionPurchaseCO@itemSum');
    Route::post('reqPurchase','RequisitionPurchaseCO@reqPurchaseStore');

//    Route::get('edit/{id}','EditRequisitionCO@edit');
//    Route::post('updateRequisition','EditRequisitionCO@update');
//
    Route::get('approvePurchaseIndex','ApprovePurchaseCO@index');
    Route::get('purDataForApprove','ApprovePurchaseCO@getPurchaseData');
    Route::post('approve/{id}','ApprovePurchaseCO@approve');
//    Route::post('reject/{id}','ApproveRequisitionCO@reject');
//
//    Route::get('printReqIndex','PrintRequisitionCO@index');
////
//    Route::get('reqDataForPrint','PrintRequisitionCO@getPrintReqData');
////
//    Route::get('print/{id}','PrintRequisitionCO@print');
//    Route::delete('products/delete/{id}','ProductsCO@destroy');


});



// RECEIVES ROUTES


Route::group(['prefix' => 'receive', 'namespace' => 'Inventory\Receives', 'middleware' => ['auth']], function () {

    Route::get('receivePurchaseIndex','ReceiveAgainstPurchaseCO@index');
    Route::get('receiveItemsData','ReceiveAgainstPurchaseCO@getData');
    Route::get('view/{id}','ReceiveAgainstPurchaseCO@view');
    Route::post('receivePurchaseItems','ReceiveAgainstPurchaseCO@store');


    // Approve Receive


    Route::get('receiveApproveIndex','ApproveReceiveCO@index');
    Route::get('receiveDBData','ApproveReceiveCO@getRData');

    Route::get('viewItems/{id}','ApproveReceiveCO@ajaxData');
    Route::post('approveItems','ApproveReceiveCO@approve');




});




// SALES ROUTES


Route::group(['prefix' => 'sales', 'namespace' => 'Inventory\Sales', 'middleware' => ['auth']], function () {

    Route::get('customerInfoIndex','CreateCustomerCO@index');
    Route::get('getCustomerInfo','CreateCustomerCO@getCustomerData');
    Route::post('customerInfoIndex','CreateCustomerCO@store');
    Route::post('customerUpdateIndex','CreateCustomerCO@update');
    Route::post('customerDeleteIndex','CreateCustomerCO@destroy');

    Route::get('salesRateIndex','SaleItemsRateCO@index');
    Route::post('updateProductRate','SaleItemsRateCO@update');
    Route::get('approveSalesRateIndex','SaleItemsRateCO@approveSalesRateIndex');
    Route::post('approveProductRate','SaleItemsRateCO@approve');
    Route::post('rejectProductRate','SaleItemsRateCO@reject');

    Route::get('createSalesInvoiceIndex','SaleInvoiceCO@index');
    Route::post('saveSalesInvoiceUniqueId/{id}','SaleInvoiceCO@uniqueID');


    Route::post('SalesInvoicePost','SaleInvoiceCO@store');
    Route::get('salesProducts','SaleInvoiceCO@autocomplete');
    Route::post('totalItem','SaleInvoiceCO@totalItem');

    Route::get('EditSalesInvoiceIndex','EditSalesInvoiceCO@index');
    Route::get('salesInvoiceData','EditSalesInvoiceCO@InvoiceData');
    Route::get('edit/{id}','EditSalesInvoiceCO@edit');
    Route::post('updateSalesInvoice','EditSalesInvoiceCO@update');

    Route::get('ApproveSalesInvoiceIndex','ApproveSalesInvoiceCO@index');
    Route::get('getApproveInvoice','ApproveSalesInvoiceCO@InvoiceApproveData');
    Route::get('view/{id}','ApproveSalesInvoiceCO@ajax_call');
    Route::post('approveSalesInvoice/{id}','ApproveSalesInvoiceCO@approve');

    //Report
    Route::get('printSalesInvoiceIndex','PrintSalesInvoiceCO@index');
    Route::get('getPrintInvoice','PrintSalesInvoiceCO@getInvoice');
    Route::get('printInvoice/{id}','PrintSalesInvoiceCO@printInvoice');

});



// Delivery ROUTES



Route::group(['prefix' => 'delivery', 'namespace' => 'Inventory\Delivery', 'middleware' => ['auth']], function () {

    Route::get('directDeliveryIndex','DirectDeliveryItemsCO@index');
    Route::post('directDeliveryIndex','DirectDeliveryItemsCO@store');
    Route::get('directDeliveryProductList','DirectDeliveryItemsCO@productList');


    Route::get('requisitionItemDeliveryIndex','RequisitionItemsDeliveryCO@index');
    Route::get('getReqItems','RequisitionItemsDeliveryCO@getReqItems');
    Route::get('viewItems/{id}','RequisitionItemsDeliveryCO@items');
    Route::post('deliveryRequisition','RequisitionItemsDeliveryCO@store');
    Route::post('customerDeleteIndex','RequisitionItemsDeliveryCO@destroy');

//    Sales Invoice delivery Route

    Route::get('salesInvoiceDeliveryIndex','SalesInvoiceDeliveryCO@index');
    Route::get('getInvoiceItems','SalesInvoiceDeliveryCO@getInvoiceItems');

    Route::get('viewInvoiceItems/{id}','SalesInvoiceDeliveryCO@ajax_call');
    Route::post('deliveryInvoiceItems/{id}','SalesInvoiceDeliveryCO@store');

// Approve Delivery


    Route::get('approveDeliveryIndex','ApproveDeliverCO@index');
    Route::get('getDeliveryItems','ApproveDeliverCO@getDeliveryItems');

    Route::get('viewDeliveryItems/{id}','ApproveDeliverCO@ajax_call');
    Route::post('ApproveDeliveryItems/{id}','ApproveDeliverCO@approve');


});


Route::group(['prefix' => 'inventory/report', 'namespace' => 'Inventory\Report', 'middleware' => ['auth']], function () {

    Route::get('productMovementRegister','ProductMovementRegisterCO@index');
    Route::get('rptStockPositionIndex','StockPositionCO@index');

    Route::get('rptSalesRegisterIndex','SalesRegisterReportCO@index');

});

// Export Routes

Route::group(['prefix' => 'export', 'namespace' => 'Inventory\Export', 'middleware' => ['auth']], function () {

    Route::get('exportContractIndex','ExportContractCO@index');
    Route::get('exportProducts','ExportContractCO@autocomplete');
    Route::post('saveExportContract','ExportContractCO@store');


    Route::get('editExportContractIndex','EditExportContractCO@index');
    Route::get('getEditContractData','EditExportContractCO@getEditContractData');
    Route::get('contract/edit/{id}','EditExportContractCO@edit');
    Route::post('updateExportContract','EditExportContractCO@update');


    Route::get('ApproveExportContractIndex','ApproveExportContractCO@index');
    Route::get('getExportContractData','ApproveExportContractCO@getExportContractData');
    Route::post('contract/approve/{id}','ApproveExportContractCO@approve');
    Route::post('contract/reject/{id}','ApproveExportContractCO@reject');


    // Delivery
    Route::get('deliveryExportProductIndex','DeliveryExportProductCO@index');
    Route::get('delivery/items/{id}','DeliveryExportProductCO@getExportProdDT');
    Route::get('delivery/products/{id}','DeliveryExportProductCO@getExportProductsDT');
    Route::post('storeExportProducts','DeliveryExportProductCO@store');
    Route::delete('items/delete/{id}','DeliveryExportProductCO@destroy');

    // Approve Delivery
    Route::get('approveExportDeliveryIndex','ApproveExportDeliveryCO@index');
    Route::post('approveExportDeliveryIndex','ApproveExportDeliveryCO@approve');


    // Invoice
    Route::get('createExportInvoiceIndex','ExportInvoiceCO@index');
    Route::post('invoice/store','ExportInvoiceCO@store');
    Route::get('approveExportInvoiceIndex','ExportInvoiceCO@approveIndex');
    Route::post('approveExportInvoiceIndex','ExportInvoiceCO@approve');

//    Route::get('invoice/items/{id}','ExportInvoiceCO@getExportProdDT');
//    Route::post('storeExportProducts','ExportInvoiceCO@store');
//    Route::delete('items/delete/{id}','ExportInvoiceCO@destroy');

    Route::get('updateVehicleNoIndex','UpdateTransportVehicleCO@index');
    Route::post('updateVehicleNo/{id}','UpdateTransportVehicleCO@store');

    Route::get('uploadShipmentFileIndex','UploadShipmentFileCO@index');
    Route::post('uploadShipmentFile','UploadShipmentFileCO@store');

    Route::get('updateShipmentDataIndex','ShippingInfoCO@index');
    Route::post('updateShipmentData','ShippingInfoCO@store');
    Route::post('updateContainerData/{id}','ShippingInfoCO@container');





    Route::get('report/exportReportsIndex','Report\ExportReportsIndexCO@index');
    Route::get('report/exportCommercialInvoiceReportIndex','Report\ExportReportsIndexCO@invoice');

    Route::get('report/exportPackingListIndex','Report\ExportReportsIndexCO@packingList');




});










Route::group(['prefix' => 'inventory/import', 'namespace' => 'Inventory\Import', 'middleware' => ['auth']], function () {

    Route::get('OpenImportLCIndex','ImportLCRegisterCO@index');
    Route::get('importProducts','ImportLCRegisterCO@autocomplete');

    Route::post('totalItem','ImportLCRegisterCO@totalItem');
    Route::post('saveImportLCIndex','ImportLCRegisterCO@store');

    Route::get('ApproveImportLCIndex','ApproveImportLCCO@index');


});



Route::group(['prefix' => 'production', 'namespace' => 'Inventory\Production', 'middleware' => ['auth']], function () {

    Route::get('setCurrentProductionIndex','ProductionSetupCO@index');
    Route::post('setCurrentProduction','ProductionSetupCO@update');
//
    Route::get('productionFromLineOneIndex','ProductionReceiveCO@index');
    Route::post('submitWeight','ProductionReceiveCO@view');
    Route::post('save','ProductionReceiveCO@store');


//    Route::get('productionFromLineIndex/{line}','ProductionReceiveCO@index');
//    Route::post('submitWeight','ProductionReceiveCO@view');
//    Route::post('save','ProductionReceiveCO@store');


    Route::get('productionFromFactoryIndex','ProductionFromFactoryCO@index');
    Route::post('productionFromFactory','ProductionFromFactoryCO@store');

    Route::get('editProductionFromFactoryIndex','EditProductionCO@index');
    Route::post('editProductionFromFactory','EditProductionCO@store');

    Route::get('transformProductionItemIndex','TransformProductionItemCO@index');
    Route::post('transformProductionItem','TransformProductionItemCO@store');


});
