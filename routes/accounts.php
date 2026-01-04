<?php

use App\Http\Controllers\Accounts\{
    AccountsSetup,
    GroupOneController,
    GroupTwoController,
    GroupThreeController,
    ChartOfAccountController,
    CodesParamController,
    MoneyReceiptController,
    SupplierController,
    SupplierInvoiceController,
    SupplierPayaleController,
    VoucherheaderController,
    AccountsReportController,
};
use Illuminate\Support\Facades\Route;

Route::middleware(['verified', 'auth','isBanned','UserActivity'])->group(function () {
    //Accounts Setting
    Route::controller(AccountsSetup::class)
        ->prefix('accountssetting')
        ->group(
            function () {
                // Group One index Route
                Route::get('/', 'index')->name('accsetting.GroupOne');
                // Group Tow index Route
                Route::get('/{GroupOne}/Grouptwo', 'Grouptwo')->name('accsetting.GroupTwo');
                // Group Three index Route
                Route::get('/{GroupOne}/{GroupTwo}/Groupthree', 'Groupthree')->name('accsetting.GroupThree');
            }
        );
    
    //Group One Route
    Route::controller(GroupOneController::class)
        ->prefix('groupOne')
        ->group(
            function () {
                Route::post('/store', 'store')->name('GroupOne.store');

                Route::get('/{groupOne}/edit', 'edit')->name('GroupOne.edit');
                Route::put('/{groupOne}', 'update')->name('GroupOne.update');
                Route::put('/{groupOne}/status', 'updateStatus')->name('GroupOne.updateStatus');

                Route::get('/{groupOne}', 'show')->name('GroupOne.show');
                Route::delete('/show/{groupOne}', 'destroy')->name('GroupOne.destroy');
            }
        );
    
    //Group Two Route
    Route::controller(GroupTwoController::class)
        ->prefix('Grouptwo')
        ->group(
            function () {
                Route::post('/store', 'store')->name('GroupTwo.store');
                
                Route::get('/{groupTwo}/edit', 'edit')->name('GroupTwo.edit');
                Route::put('/{groupTwo}', 'update')->name('GroupTwo.update');
                Route::put('/{groupTwo}/status', 'updateStatus')->name('GroupTwo.updateStatus');

                Route::get('/{groupTwo}', 'show')->name('GroupTwo.show');
                Route::delete('/show/{groupTwo}', 'destroy')->name('GroupTwo.destroy');
            }
        );
    
    //Group Three Route
    Route::controller(GroupThreeController::class)
        ->prefix('Groupthree')
        ->group(
            function () {
                Route::post('/store', 'store')->name('GroupThree.store');
                
                Route::get('/{groupThree}/edit', 'edit')->name('GroupThree.edit');
                Route::put('/{groupThree}', 'update')->name('GroupThree.update');
                Route::put('/{groupThree}/status', 'updateStatus')->name('GroupThree.updateStatus');

                Route::get('/{groupThree}', 'show')->name('GroupThree.show');
                Route::delete('/show/{groupThree}', 'destroy')->name('GroupThree.destroy');
            }
        );
    //chart Of Account Route
    Route::controller(ChartOfAccountController::class)
        ->prefix('chartOfAccount')
        ->group(
            function () {

                Route::get('/', 'index')->name('chartOfAccount.index');
                Route::get('/getGroupTwo/{GroupOne}', 'getGroupTwo')->name('chartOfAccount.getGroupTwo');
                Route::get('/getGroupThree/{GroupOne}/{GroupTwo}', 'getGroupThree')->name('chartOfAccount.getGroupThree');
                Route::get('/generateAccountCode/{groupthree}', 'generateCode')->name('chartOfAccount.generateCode');

                Route::post('/store', 'store')->name('chartOfAccount.store');
                               
                Route::get('/{chartOfAccount}/edit', 'edit')->name('chartOfAccount.edit');
                Route::put('/{chartOfAccount}', 'update')->name('chartOfAccount.update');
                Route::put('/{chartOfAccount}/status', 'updateStatus')->name('chartOfAccount.updateStatus');

                Route::get('/{chartOfAccount}', 'show')->name('chartOfAccount.show');
                Route::delete('/show/{chartOfAccount}', 'destroy')->name('chartOfAccount.destroy');
            }
        );
    //AC TO GL Setup
    Route::controller(CodesParamController::class)
        ->prefix('actoglsetup')
        ->group(
            function () {
                Route::get('/', 'index')->name('actoglsetup.index');
                Route::post('/store', 'store')->name('actoglsetup.store');
                Route::get('/{codesParam}', 'show')->name('actoglsetup.show');
                Route::delete('/show/{codesParam}', 'destroy')->name('actoglsetup.destroy');
                Route::get('/{codesParam}/edit', 'edit')->name('actoglsetup.edit');
                Route::put('/{codesParam}', 'update')->name('actoglsetup.update');
                Route::put('/{codesParam}/status', 'updateStatus')->name('actoglsetup.updateStatus');
            }
        );
    //Money Receipt Route
    Route::controller(MoneyReceiptController::class)
        ->prefix('invoicelist')
        ->group(
            function () {
                Route::get('/AllInvoiceList', 'AllInvoiceList')->name('invoicelist.AllInvoiceList');
                Route::get('/DueInvoiceList', 'DueInvoiceList')->name('invoicelist.DueInvoiceList');
                Route::get('/MRList', 'MRList')->name('invoicelist.MRList');
                
                Route::get('/{insid}/createmr/{sid}', 'createMR')->name('invoicelist.createMR');
                Route::post('/{insnumber}/storeMR/{student}', 'storeMR')->name('invoicelist.storeMR');
                Route::get('/{confirm}/onView', 'onView')->name('invoicelist.onView');
                Route::post('/{confirm}/onCancel', 'onCancel')->name('invoicelist.onCancel');
                Route::post('/{confirm}/onConfirm', 'onConfirm')->name('invoicelist.onConfirm');
                Route::get('/{onReport}/onReport', 'onReport')->name('invoicelist.onReport');
            }
        );
    //Supplier Route
    Route::controller(SupplierController::class)
        ->prefix('suppliers')
        ->group(
            function () {
                Route::get('/', 'index')->name('suppliers.index');
                Route::post('/store', 'store')->name('suppliers.store');
                Route::get('/{supplier}', 'show')->name('suppliers.show');
                Route::delete('/show/{supplier}', 'destroy')->name('suppliers.destroy');
                Route::get('/{supplier}/edit', 'edit')->name('suppliers.edit');
                Route::put('/{supplier}', 'update')->name('suppliers.update');
                Route::put('/{supplier}/status', 'updateStatus')->name('suppliers.updateStatus');
            }
        );
    //Supplier Invoice Route
    Route::controller(SupplierInvoiceController::class)
        ->prefix('suppliersInvoice')
        ->group(
            function () {
                Route::get('/', 'index')->name('suppliersInvoice.index');
                Route::post('/store', 'store')->name('suppliersInvoice.store');
                Route::get('/{supplier_invocie}', 'show')->name('suppliersInvoice.show');
                Route::delete('/show/{supplier_invocie}', 'destroy')->name('suppliersInvoice.destroy');
                Route::get('/{supplier_invocie}/edit', 'edit')->name('suppliersInvoice.edit');
                Route::put('/{supplier_invocie}', 'update')->name('suppliersInvoice.update');
                Route::put('/{supplier_invocie}/status', 'Confirm')->name('suppliersInvoice.Confirm');
            }
        );
    //Supplier Payable Route
    Route::controller(SupplierPayaleController::class)
        ->prefix('suppliersPayble')
        ->group(
            function () {
                Route::get('/', 'index')->name('suppliersPayble.index');
                Route::post('/store', 'store')->name('suppliersPayble.store');
                Route::get('/{supplier_payment}', 'show')->name('suppliersPayble.show');
                Route::delete('/show/{supplier_payment}', 'destroy')->name('suppliersPayble.destroy');
                Route::get('/{supplier_payment}/edit', 'edit')->name('suppliersPayble.edit');
                Route::put('/{supplier_payment}', 'update')->name('suppliersPayble.update');
                Route::put('/{supplier_payment}/status', 'Confirm')->name('suppliersPayble.Confirm');
            }
        );
     //Voucher header Route
    Route::controller(VoucherheaderController::class)
        ->prefix('voucherheader')
        ->group(
            function () {
                // Jurnal Voucher Route
                Route::get('/allvoucher', 'allVoucher')->name('voucherheader.allvoucher');
                Route::get('/{allvoucher}/edit', 'allvoucherEdit')->name('voucherheader.allvoucherEdit');
                Route::put('/{allvoucher}', 'allvoucherUpdate')->name('voucherheader.allvoucherUpdate');
                Route::put('/{allvoucher}/status', 'allvoucherConfirm')->name('voucherheader.allvoucherConfirm');
                Route::put('/{allvoucher}/balance', 'allvoucherBalance')->name('voucherheader.allvoucherBalance');
                // Opening Voucher Route
                Route::get('/opening', 'openingVoucher')->name('voucherheader.opening');
                Route::post('/opening', 'openingStore')->name('voucherheader.openingStore');
                Route::get('/{opening}/edit', 'openingEdit')->name('voucherheader.openingEdit');
                Route::put('/{opening}', 'openingUpdate')->name('voucherheader.openingUpdate');
                Route::put('/{opening}/status', 'openingConfirm')->name('voucherheader.openingConfirm');
                // Jurnal Voucher Route
                Route::get('/jurnal', 'jurnalVoucher')->name('voucherheader.jurnal');
                Route::post('/jurnal', 'jurnalStore')->name('voucherheader.jurnalStore');
                Route::get('/{jurnal}/edit', 'jurnalEdit')->name('voucherheader.jurnalEdit');
                Route::put('/{jurnal}', 'jurnalUpdate')->name('voucherheader.jurnalUpdate');
                Route::put('/{jurnal}/status', 'jurnalConfirm')->name('voucherheader.jurnalConfirm');
                // Payment Voucher Route
                Route::get('/payment', 'paymentVoucher')->name('voucherheader.payment');
                Route::post('/payment', 'paymentStore')->name('voucherheader.paymentStore');
                Route::get('/{payment}/edit', 'paymentEdit')->name('voucherheader.paymentEdit');
                Route::put('/{payment}', 'paymentUpdate')->name('voucherheader.paymentUpdate');
                Route::put('/{payment}/status', 'paymentConfirm')->name('voucherheader.paymentConfirm');
                // Receipt Voucher Route
                Route::get('/receipt', 'receiptVoucher')->name('voucherheader.receipt');
                Route::post('/receipt', 'receiptStore')->name('voucherheader.receiptStore');
                Route::get('/{receipt}/edit', 'receiptEdit')->name('voucherheader.receiptEdit');
                Route::put('/{receipt}', 'receiptUpdate')->name('voucherheader.receiptUpdate');
                Route::put('/{receipt}/status', 'receiptConfirm')->name('voucherheader.receiptConfirm');
                // Reverse Voucher Route
                Route::get('/reverse', 'reverseVoucher')->name('voucherheader.reverse');
                Route::post('/reverse', 'reverseStore')->name('voucherheader.reverseStore');
                Route::get('/{reverse}/edit', 'reverseEdit')->name('voucherheader.reverseEdit');
                Route::put('/{reverse}', 'reverseUpdate')->name('voucherheader.reverseUpdate');
                Route::put('/{reverse}/status', 'reverseConfirm')->name('voucherheader.reverseConfirm');
                //single voucher report
                Route::get('/single/{voucherID}', 'singleReport')->name('voucherheader.singleReport');
                
            }
        );

    //Accounts Report Route
    Route::controller(AccountsReportController::class)
        ->prefix('accountsreport')
        ->group(
            function () {
                Route::get('/', 'index')->name('accountsreport.index');
                Route::get('/chartOfAccountReport', 'chartOfAccountReport')->name('accountsreport.chartOfAccountReport');
            }
        );
});
