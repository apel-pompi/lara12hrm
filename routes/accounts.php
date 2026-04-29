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

Route::middleware(['verified', 'auth', 'isBanned', 'UserActivity'])->group(function () {
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
                Route::get('/{supplier_payment}/{supplier_name}/{group_three_description}/{branch_id}', 'FetchPayment')->name('suppliersPayble.FetchPayment');
                Route::post('/store', 'store')->name('suppliersPayble.store');
                Route::get('/manage', 'managePayable')->name('suppliersPayble.manage');
            }
        );
    //Voucher header Route
    Route::controller(VoucherheaderController::class)
        ->prefix('voucherheader')
        ->group(
            function () {
                // All Voucher Route
                Route::get('/allvoucher', 'allVoucher')->name('voucherheader.allvoucher');
                Route::put('/{allvoucher}/status', 'allvoucherConfirm')->name('voucherheader.allvoucherConfirm');
                Route::put('/{allvoucher}/balance', 'allvoucherBalance')->name('voucherheader.allvoucherBalance');
                // Opening Voucher Route
                Route::get('/opening', 'openingVoucher')->name('voucherheader.opening');
                Route::post('/opening', 'openingStore')->name('voucherheader.openingStore');
                Route::get('/{opening}/openingEdit', 'openingEdit')->name('voucherheader.openingEdit');
                Route::put('/{opening}/openingUpdate', 'openingUpdate')->name('voucherheader.openingUpdate');
                Route::put('/{opening}/openingStatus', 'openingConfirm')->name('voucherheader.openingConfirm');
                // Jurnal Voucher Route
                Route::get('/jurnal', 'jurnalVoucher')->name('voucherheader.jurnal');
                Route::post('/jurnal', 'jurnalStore')->name('voucherheader.jurnalStore');
                Route::get('/{jurnal}/jurnalEdit', 'jurnalEdit')->name('voucherheader.jurnalEdit');
                Route::put('/{jurnal}/jurnalUpdate', 'jurnalUpdate')->name('voucherheader.jurnalUpdate');
                Route::put('/{jurnal}/jurnalStatus', 'jurnalConfirm')->name('voucherheader.jurnalConfirm');
                // Payment Voucher Route
                Route::get('/payment', 'paymentVoucher')->name('voucherheader.payment');
                Route::post('/payment', 'paymentStore')->name('voucherheader.paymentStore');
                Route::get('/{payment}/paymentEdit', 'paymentEdit')->name('voucherheader.paymentEdit');
                Route::put('/{payment}/paymentUpdate', 'paymentUpdate')->name('voucherheader.paymentUpdate');
                Route::put('/{payment}/paymentStatus', 'paymentConfirm')->name('voucherheader.paymentConfirm');
                // Receipt Voucher Route
                Route::get('/receipt', 'receiptVoucher')->name('voucherheader.receipt');
                Route::post('/receipt', 'receiptStore')->name('voucherheader.receiptStore');
                Route::get('/{receipt}/receiptEdit', 'receiptEdit')->name('voucherheader.receiptEdit');
                Route::put('/{receipt}/receiptUpdate', 'receiptUpdate')->name('voucherheader.receiptUpdate');
                Route::put('/{receipt}/receiptStatus', 'receiptConfirm')->name('voucherheader.receiptConfirm');
                // Reverse Voucher Route
                Route::get('/reverse', 'reverseVoucher')->name('voucherheader.reverse');
                Route::post('/reverse', 'reverseStore')->name('voucherheader.reverseStore');
                Route::get('/{reverse}/reverseEdit', 'reverseEdit')->name('voucherheader.reverseEdit');
                Route::put('/{reverse}/reverseUpdate', 'reverseUpdate')->name('voucherheader.reverseUpdate');
                Route::put('/{reverse}/reverseStatus', 'reverseConfirm')->name('voucherheader.reverseConfirm');
                //single voucher report
                Route::get('/single/{voucherID}', 'singleReport')->name('voucherheader.singleReport');
                Route::get('/balance/{accountcode}', 'getAccountBalance')->name('voucherheader.balance');
            }
        );

    //Accounts Report Route
    Route::controller(AccountsReportController::class)
        ->prefix('accountsreport')
        ->group(
            function () {
                Route::get('/', 'index')->name('accountsreport.index');
                Route::get('/chartOfAccountReport', 'chartOfAccountReport')->name('accountsreport.chartOfAccountReport');
                Route::get('/CashBook', 'CashBook')->name('accountsreport.CashBook');
                Route::get('/CashBookReport', 'CashBookReport')->name('accountsreport.CashBookReport');
                Route::get('/CashFlow', 'CashFlow')->name('accountsreport.CashFlow');
                Route::get('/CashFlowReport', 'CashFlowReport')->name('accountsreport.CashFlowReport');
                Route::get('/ActoGL', 'ActoGL')->name('accountsreport.ActoGL');
                Route::get('/ActoGLReport', 'ActoGLReport')->name('accountsreport.ActoGLReport');
                Route::get('/SupplierLedger', 'SupplierLedger')->name('accountsreport.SupplierLedger');
                Route::get('/SupplierLedgerReport', 'SupplierLedgerReport')->name('accountsreport.SupplierLedgerReport');
                Route::get('/JurnalTransactions', 'JurnalTransactions')->name('accountsreport.JurnalTransactions');
                Route::get('/JurnalTransactionsReport', 'JurnalTransactionsReport')->name('accountsreport.JurnalTransactionsReport');
                Route::get('/trialbalanceconsolidated', 'TrialBalanceConsolidated')->name('accountsreport.trialbalanceconsolidated');
                Route::get('/trialbalanceconsolidatedreport', 'TrialBalanceConsolidatedReport')->name('accountsreport.trialbalanceconsolidatedreport');

                Route::get('/trialbalance', 'TrialBalance')->name('accountsreport.trialbalance');
                Route::get('/trialbalancereport', 'TrialBalanceReport')->name('accountsreport.trialbalancereport');

                Route::get('/balancesheet', 'BalanceSheet')->name('accountsreport.balancesheet');
                Route::get('/balancesheetreport', 'BalanceSheetReport')->name('accountsreport.balancesheetreport');

                Route::get('/profitloss', 'ProfitLoss')->name('accountsreport.ProfitLoss');
                Route::get('/profitlossreport', 'ProfitLossReport')->name('accountsreport.ProfitLossreport');
            }
        );
});
