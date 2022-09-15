<?php

use App\Http\Controllers\AccountingController;
use App\Http\Controllers\AccountOpeningController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DistributerController;
use App\Http\Controllers\Admin\DistributerMilkController;
use App\Http\Controllers\Admin\DistributersellController;
use App\Http\Controllers\Admin\DistributerSnfFatController;
use App\Http\Controllers\Admin\DistributorPaymentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EmployeeSalesController;
use App\Http\Controllers\Admin\ExtraIncomeController;
use App\Http\Controllers\Admin\FarmerController;
use App\Http\Controllers\Admin\FarmerDetailController;
use App\Http\Controllers\Admin\GatewayController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\ItemCategoryController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\ItemStockController;
use App\Http\Controllers\Admin\ItemVariantController;
use App\Http\Controllers\Admin\Manufacture\ManufactreProductController;
use App\Http\Controllers\Admin\Manufacture\ManufactureProcessController;
use App\Http\Controllers\Admin\MilkController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\PackagingController;
use App\Http\Controllers\Admin\PointController;
use App\Http\Controllers\Admin\PosBillingController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SellitemController;
use App\Http\Controllers\Admin\Setting\ConversionController;
use App\Http\Controllers\Admin\SimpleManufactureController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\SupplierBillController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WastageController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BankTransactionController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\Billing\BillingController as BillingBillingController;
use App\Http\Controllers\DayReportController;
use App\Http\Controllers\FarmerReportController;
use App\Http\Controllers\FixedAssetController;
use App\Http\Controllers\HomeController as ControllersHomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\POS\BillingController;
use App\Http\Controllers\POS\POSController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\RestaurantReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sahakari\HomeController;
use App\Http\Controllers\Sahakari\Member\MemberController;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\SummaryController;
use App\Models\BankTransaction;
use App\Models\ExtraIncome;
use App\Models\ExtraIncomeCategory;
use App\Models\Item;
use App\Models\Sahakari\HomeCntroller;
use App\NepaliDate;

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

Route::get('/', [ControllersHomeController::class, 'home']);
Route::get('/test/{id}', 'TestController@index')->name('test');
Route::get('/test-all/{id}', 'TestController@all')->name('test-all');
Route::get('/test-distributor', 'TestController@distributor')->name('test-distributor');
Route::get('/test-distributor-date', 'TestController@distributorByDate')->name('test-distributor');
Route::view('/403', 'access.403')->name('403');

Route::get('/pass', function () {
    //$pass = bcrypt('55ryx82aEu8cVE3');
    //echo $pass;
    // dd($pass);
    // $id=1;
    // $data= sprintf("%'.09d",$id);
    // $arr=str_split($data,3);
    // echo implode('/',$arr);
    // dd(currentStock()->sum);
    // echo lorem();
    dd(getVATAC(), getTDSAC());
});




Route::post('cuurent-stock', function () {
    return response(currentStock()->sum);
})->name('current-stock');




Route::match(['get', 'post'], 'login', 'AuthController@login')->name('login');
Route::match(['get', 'post'], 'logout', 'AuthController@logout')->name('logout');

Route::name('admin.')->group(function () {

    Route::group(['middleware' => 'role:admin', 'prefix' => 'admin'], function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('collection-centers')->middleware('permmission:02.12')->name('center.')->group(function () {
            // XXX collection centers
            Route::get('', 'Admin\CenterController@index')->name('index');
            Route::post('add', 'Admin\CenterController@addCollectionCenter')->name('add');
            Route::get('collection-center-list', 'Admin\CenterController@listCenter')->name('list.center');
            Route::post('delete', 'Admin\CenterController@deleteCenter')->name('delete')->middleware('authority');
            Route::post('update', 'Admin\CenterController@updateCollectionCenter')->name('update')->middleware('authority');
        });


        // XXX farmer
        Route::prefix('farmers')->name('farmer.')->group(function () {
            // XXX farmer passbook
            Route::prefix('passbook')->name('passbook.')->middleware('permmission:01.11')->group(function () {
                Route::get('', [FarmerDetailController::class, 'index'])->name('index');
                Route::post('data', [FarmerDetailController::class, 'data'])->name('data');
                Route::post('close', [FarmerDetailController::class, 'close'])->name('close');
                Route::post('updateData', [FarmerDetailController::class, 'updateData'])->name('updateData');
                Route::match(['GET', 'POST'], 'notClosed', [FarmerDetailController::class, 'notClosed'])->name('notClosed');
                Route::match(['GET', 'POST'], 'close-notClosed', [FarmerDetailController::class, 'closeNotClosed'])->name('close.notClosed');
            });
            // XXX farmer routes
            Route::get('', [FarmerController::class, 'index'])->name('list')->middleware('permmission:01.01');
            Route::match(["GET", "POST"], 'switch', [FarmerController::class, 'switch'])->name('switch')->middleware('permmission:01.01');
            Route::match(["GET", "POST"], 'printSlip', [FarmerController::class, 'printSlip'])->name('printSlip')->middleware('permmission:01.01');
            Route::match(["GET", "POST"], 'switchSave', [FarmerController::class, 'switchSave'])->name('switchSave')->middleware('permmission:01.01');
            Route::post('list-by-center', [FarmerController::class, 'listFarmerByCenter'])->name('list-bycenter');
            Route::post('minlist-by-center', [FarmerController::class, 'minlistFarmerByCenter'])->name('minlist-bycenter');


            Route::get('detail/{id}', [FarmerController::class, 'farmerDetail'])->name('detail');
            Route::post('update', [FarmerController::class, 'updateFarmer'])->name('update')->middleware('permmission:01.03');
            Route::post('add', [FarmerController::class, 'addFarmer'])->name('add')->middleware('permmission:01.02');
            Route::get('delete/{id}', [FarmerController::class, 'deleteFarmer'])->name('delete')->middleware('permmission:01.04');
            Route::post('load-session-data', [FarmerController::class, 'loadSessionData'])->name('load-session-data');

            // XXX farmer due payments
            Route::get('due', [FarmerController::class, 'due'])->name('due')->middleware('permmission:01.05');
            Route::post('due-load', [FarmerController::class, 'dueLoad'])->name('due.load')->middleware('permmission:01.05');
            Route::post('pay-save', [FarmerController::class, 'paymentSave'])->name('pay.save')->middleware('permmission:01.05');
            Route::post('pay-delete', [FarmerController::class, 'paymentDelete'])->name('pay.delete')->middleware('permmission:01.05');

            //XXX farmer Account opening

            Route::match(['GET', 'POST'], 'add-due-list', [FarmerController::class, 'addDueList'])->name('due.add.list')->middleware('permmission:01.06');
            Route::match(['GET', 'POST'], 'add-due', [FarmerController::class, 'addDue'])->name('due.add')->middleware('permmission:01.06');

            // XXX  farmer advance
            Route::get('advances', 'Admin\AdvanceController@index')->name('advance')->middleware('permmission:01.10');
            Route::post('advance-add', 'Admin\AdvanceController@add')->name('advance.add')->middleware('permmission:01.10');
            Route::post('advance-list', 'Admin\AdvanceController@list')->name('advance.list')->middleware('permmission:01.10');

            // Route::post('advance-update', 'Admin\AdvanceController@update')->name('advance.update')->middleware('authority');
            Route::post('advance-delete', 'Admin\AdvanceController@delete')->name('advance.delete')->middleware('permmission:01.10');
            Route::post('advance-update', 'Admin\AdvanceController@update')->name('advance.update')->middleware('permmission:01.10');
            Route::post('advance-list-by-date', 'Admin\AdvanceController@listByDate')->name('advance.list-by-date')->middleware('permmission:01.10');
            // XXX Milk payment
            Route::group(['prefix' => 'milk-payment'], function () {
                Route::name('milk.payment.')->group(function () {
                    Route::match(['GET', 'POST'], '', 'Admin\MilkPaymentController@index')->name('index');
                    // Route::post('load','Admin\ProductController@index')->name('load');
                    Route::post('add', 'Admin\MilkPaymentController@add')->name('add');
                    Route::match(['GET', 'POST'], 'update/{id}', 'Admin\MilkPaymentController@update')->name('update')->middleware('authority');
                    Route::post('delete', 'Admin\MilkPaymentController@delete')->name('delete')->middleware('authority');
                    // Route::post('del','Admin\ProductController@del')->name('del')->middleware('authority');
                });
            });
        });

        Route::prefix('snf-fat')->name('snf-fat.')->group(function () {

            // XXX snf and fats
            Route::get('', 'Admin\SnffatController@index')->name('index')->middleware('permmission:02.04');
            Route::post('load-data', 'Admin\SnffatController@snffatDataLoad')->name('load-data')->middleware('permmission:02.04');
            Route::post('snf-fats-save', 'Admin\SnffatController@saveSnffatData')->name('store')->middleware('permmission:02.04');
            Route::post('snf-fats-update', 'Admin\SnffatController@update')->name('update')->middleware('permmission:02.05');
            Route::post('snf-fats-delete', 'Admin\SnffatController@delete')->name('delete')->middleware('permmission:02.06');
        });

        Route::prefix('milk-data')->name('milk.')->group(function () {
            // XXX milk data
            Route::get('', 'Admin\MilkController@index')->name('index')->middleware('permmission:02.01');
            Route::post('save/{type}', 'Admin\MilkController@saveMilkData')->name('store')->middleware('permmission:02.01');
            Route::post('load', 'Admin\MilkController@milkDataLoad')->name('load')->middleware('permmission:02.01');
            Route::post('update', 'Admin\MilkController@update')->name('update')->middleware('permmission:02.02');
            Route::post('delete', 'Admin\MilkController@delete')->name('delete')->middleware('permmission:02.03');
            Route::post('farmer-data-load', 'Admin\MilkController@loadFarmerData')->name('load.farmer.data');

            Route::middleware(['permmission:02.07'])->group(function () {

                Route::match(['get', 'post'], 'milkfatsnf', [MilkController::class, 'milkfatsnf'])->name('milkfatsnf');
                Route::post('milkfatsnfSave', [MilkController::class, 'milkfatsnfSave'])->name('milkfatsnfSave');
                Route::post('milkfatsnfDel', [MilkController::class, 'milkfatsnfDel'])->name('milkfatsnfDel');
            });
            Route::middleware(['permmission:02.08'])->group(function () {
                Route::match(['get', 'post'], 'milkfatsnfname', [MilkController::class, 'milkfatsnfname'])->name('milkfatsnfname');
                Route::post('milkfatsnfnameSave', [MilkController::class, 'milkfatsnfnameSave'])->name('milkfatsnfnameSave');
                Route::post('milkfatsnfnameDel', [MilkController::class, 'milkfatsnfnameDel'])->name('milkfatsnfnameDel');
            });


            Route::match(['GET', 'POST'], 'chalan', [MilkController::class, 'chalan'])->name('chalan');
            Route::match(['GET', 'POST'], 'chalan-save', [MilkController::class, 'chalanSave'])->name('chalan-save');
        });

        Route::prefix('distributers')->name('distributer.')->group(function () {
            // XXX distributer
            Route::get('', [DistributerController::class, 'index'])->name('index')->middleware('permmission:04.01');
            Route::post('add', [DistributerController::class, 'add'])->name('add')->middleware('permmission:04.02');
            Route::get('list', [DistributerController::class, 'list'])->name('list');
            Route::post('update', [DistributerController::class, 'update'])->name('update')->middleware('permmission:04.03');
            Route::post('delete', [DistributerController::class, 'delete'])->name('delete')->middleware('permmission:04.04');
            Route::middleware('permmission:04.04')->group(function () {

                Route::get('detail/{id}', [DistributerController::class, 'distributerDetail'])->name('detail');
                Route::post('detail', [DistributerController::class, 'distributerDetailLoad'])->name('detail.load');
            });


            Route::get('opening', [DistributerController::class, 'opening'])->name('detail.opening')->middleware('permmission:04.06');
            Route::post('opening/list', [DistributerController::class, 'loadLedger'])->name('detail.opening.list');
            Route::post('ledger', [DistributerController::class, 'ledger'])->name('detail.ledger');
            Route::post('ledger/update', [DistributerController::class, 'updateLedger'])->name('detail.ledger.update')->middleware('authority');

            // distributer request
            Route::get('request', [DistributerController::class, 'distributerRequest'])->name('request');
            Route::get('change/status/{id}', [DistributerController::class, 'distributerRequestChangeStatus'])->name('request.change.status');

            Route::match(['GET', 'POST'], 'credit-list', [DistributerController::class, 'creditList'])->name('credit.list')->middleware('permmission:04.10');



            // XXX distributer sell
            Route::middleware('permmission:04.09')->group(function () {
                Route::get('sells', [DistributersellController::class, 'index'])->name('sell');
                Route::post('sell-add', [DistributersellController::class, 'addDistributersell'])->name('sell.add');
                Route::post('sell-list', [DistributersellController::class, 'listDistributersell'])->name('sell.list');
                Route::post('sell-del', [DistributersellController::class, 'deleteDistributersell'])->name('sell.del')->middleware('authority');
            });

            Route::middleware('permmission:04.05')->group(function () {
                //XXX Distributor Payments
                Route::get('payment', [DistributorPaymentController::class, 'index'])->name('payemnt');
                Route::post('payment-del/{id}', [DistributorPaymentController::class, 'del'])->name('payemnt.del');
                Route::post('due-list', [DistributorPaymentController::class, 'due'])->name('due');
                Route::post('due-pay', [DistributorPaymentController::class, 'pay'])->name('pay');
            });
            //XXXX Distributer Milk Data
            Route::prefix('milk-data')->name('MilkData.')->middleware('permmission:04.07')->group(function () {
                Route::match(['get', 'post'], '', [DistributerMilkController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'add', [DistributerMilkController::class, 'add'])->name('add');
                Route::match(['get', 'post'], 'update', [DistributerMilkController::class, 'update'])->name('update');
                Route::match(['get', 'post'], 'delete', [DistributerMilkController::class, 'delete'])->name('delete');
                Route::match(['get', 'post'], 'add-to-ledger', [DistributerMilkController::class, 'addToLedger'])->name('addToLedger');
            });
            //XXXX Distributer Milk Data
            Route::prefix('snf-fat')->name('snffat.')->middleware('permmission:04.08')->group(function () {
                Route::match(['get', 'post'], '', [DistributerSnfFatController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'add', [DistributerSnfFatController::class, 'add'])->name('add');
                Route::match(['get', 'post'], 'update', [DistributerSnfFatController::class, 'update'])->name('update');
                Route::match(['get', 'post'], 'delete', [DistributerSnfFatController::class, 'delete'])->name('delete');
            });
        });
        // XXX categories
        Route::prefix('categories')->name('category.')->group(function () {
            Route::match(['GET', 'POST'], '', [CategoryController::class, 'index'])->name('index');
            Route::post('edit', [CategoryController::class, 'edit'])->name('edit');
            Route::post('add', [CategoryController::class, 'save'])->name('save');
            Route::get('delete/{cat}', [CategoryController::class, 'delete'])->name('delete')->middleware('authority');
        });

        // XXX items
        Route::prefix('items')->name('item.')->group(function () {
            Route::match(['GET', 'POST'], '', [ItemController::class, 'index'])->name('index')->middleware('permmission:03.04');
            Route::match(['GET', 'POST'], 'all', [ItemController::class, 'all'])->name('all');
            Route::post('edit', [ItemController::class, 'edit'])->name('edit')->middleware('permmission:03.02');
            Route::post('add', [ItemController::class, 'save'])->name('save')->middleware('permmission:03.01');
            Route::match(['GET', 'POST'], 'item-center-stock/{id}', [ItemController::class, 'centerStock'])->name('center-stock')->middleware('permmission:03.05');
            Route::get('item-delete/{id}', [ItemController::class, 'delete'])->name('delete')->middleware('permmission:03.03');
            Route::post('item-update', [ItemController::class, 'update'])->name('update')->middleware('authority')->middleware('permmission:03.02');
            //xxx stock
            Route::match(['GET', 'POST'], 'stockout', [ItemController::class, 'stockOut'])->name('stockout')->middleware('permmission:03.05');;
            Route::get('stockout-list', [ItemController::class, 'stockOutList'])->name('stockout-list')->middleware('permmission:03.05');
            Route::get('stockout-view/{id}', [ItemController::class, 'stockOutView'])->name('stockout-view')->middleware('permmission:03.05');
            Route::get('stockout-cancel/{id}', [ItemController::class, 'stockOutCancel'])->name('stockout-cancel')->middleware('permmission:03.05');
            Route::get('stockout-print/{id}', [ItemController::class, 'stockOutPrint'])->name('stockout-print')->middleware('permmission:03.05');
            //stock view
            Route::match(["GET", "POST"], 'items-center-stock', [ItemStockController::class, 'index'])->name('items-center-stock')->middleware('permmission:03.07');
            Route::match(['GET', 'POST'], 'barcode', [ItemController::class, 'barcode'])->name('barcode');
            Route::match(['GET', 'POST'], 'product', [ItemController::class, 'product'])->name('product');
            Route::match(['GET', 'POST'], 'product-barcode', [ItemController::class, 'productBarcode'])->name('product-barcode');
            //XXX variants
            Route::prefix('variants')->name('variants.')->middleware('permmission:03.10')->group(function () {
                Route::match(['GET', 'POST'], 'list/{item}', [ItemVariantController::class, 'index'])->name('index');
                Route::match(['GET', 'POST'], 'add/{id}', [ItemVariantController::class, 'add'])->name('add');
                Route::match(['POST'], 'update/{id}', [ItemVariantController::class, 'update'])->name('update');
                Route::match(['POST', 'GET'], 'del/{id}', [ItemVariantController::class, 'del'])->name('del');
            });

            Route::prefix('categories')->name('categories.')->middleware('permmission:03.10')->group(function () {
                Route::match(['GET', 'POST'], 'list/{item}', [ItemCategoryController::class, 'index'])->name('index');
                Route::match(['GET', 'POST'], 'add/{id}', [ItemCategoryController::class, 'add'])->name('add');
                Route::match(['POST'], 'update/{id}', [ItemCategoryController::class, 'update'])->name('update');
                Route::match(['POST'], 'del/{id}', [ItemCategoryController::class, 'del'])->name('del');
            });

            Route::prefix('packaging')->name('packaging.')->middleware('permmission:03.08')->group(function () {
                Route::get('', [PackagingController::class, 'index'])->name('index');
                Route::match(['GET', 'POST'], 'add', [PackagingController::class, 'add'])->name('add');
                Route::match(['GET', 'POST'], 'cancel/{id}', [PackagingController::class, 'cancel'])->name('cancel');
                Route::match(['GET', 'POST'], 'view/{id}', [PackagingController::class, 'view'])->name('view');
            });
        });

        Route::prefix('sell-items')->name('sell.item.')->middleware('permmission:01.08')->group(function () {
            // XXX sell items
            Route::get('', [SellitemController::class, 'index'])->name('index');
            Route::post('add', [SellitemController::class, 'addSellItem'])->name('add');
            Route::post('list', [SellitemController::class, 'sellItemList'])->name('list');
            Route::post('update', [SellitemController::class, 'updateSellItem'])->name('update');
            Route::post('delete', [SellitemController::class, 'deleteSellitem'])->name('delete')->middleware('authority');
            Route::post('delete-all', [SellitemController::class, 'multidel'])->name('del-all-selitem')->middleware('authority');
        });


        Route::prefix('expenses')->name('expense.')->group(function () {
            //XXX expense categories
            Route::get('categories', 'Admin\ExpenseController@categoryIndex')->name('category')->middleware('permmission:06.01');
            Route::post('category-add', 'Admin\ExpenseController@categoryAdd')->name('category.add');
            Route::post('category-update', 'Admin\ExpenseController@categoryUpdate')->name('category.update');
            Route::post('category/expenses', 'Admin\ExpenseController@categoryExpenses')->name('list-category');

            // XXX  expensess
            Route::get('', 'Admin\ExpenseController@index')->name('index')->middleware('permmission:06.05');
            Route::post('expense-add', 'Admin\ExpenseController@add')->name('add');
            Route::post('update', 'Admin\ExpenseController@update')->name('update')->middleware('authority');
            Route::post('edit', 'Admin\ExpenseController@edit')->name('edit')->middleware('authority');
            Route::get('list', 'Admin\ExpenseController@list')->name('list');
            Route::post('delete', 'Admin\ExpenseController@delete')->name('delete')->middleware('authority');
            Route::post('load', 'Admin\ExpenseController@load')->name('load');
        });


        Route::prefix('suppliers')->name('supplier.')->group(function () {
            // XXX suppliers
            Route::get('', [SupplierController::class, 'index'])->name('index')->middleware('permmission:07.01');
            Route::post('add', [SupplierController::class, 'add'])->name('add');
            Route::get('list', [SupplierController::class, 'list'])->name('list');
            Route::post('delete', [SupplierController::class, 'delete'])->name('delete')->middleware('authority');
            Route::post('update', [SupplierController::class, 'update'])->name('update');
            //XXX supplier details
            Route::get('detail/{id}', [SupplierController::class, 'detail'])->name('detail');
            Route::post('load-detail', [SupplierController::class, 'loadDetail'])->name('load-detail');

            Route::get('payment', [SupplierController::class, 'payment'])->name('pay')->middleware('permmission:07.09');
            Route::post('due', [SupplierController::class, 'due'])->name('due')->middleware('permmission:07.09');;
            Route::post('due-pay', [SupplierController::class, 'duePay'])->name('due.pay')->middleware('permmission:07.09');
            Route::post('payment-delete', [SupplierController::class, 'delPayment'])->name('delete.pay')->middleware('permmission:07.11');


            // XXX supplier bills
            Route::get('bills', [SupplierController::class, 'indexBill'])->name('bill')->middleware('permmission:07.05');
            Route::match(['GET', 'POST'], 'bill-add', [SupplierController::class, 'addBill'])->name('bill.add')->middleware('permmission:07.06');
            Route::match(['GET', 'POST'], 'bill-expense', [SupplierBillController::class, 'expense'])->name('bill.expense')->middleware('permmission:07.06');
            Route::post('bill-list', [SupplierController::class, 'listBill'])->name('bill.list')->middleware('permmission:07.05');
            Route::post('bill-update', [SupplierController::class, 'updateBill'])->name('bill.update')->middleware('permmission:07.07');
            Route::get('bill-delete', [SupplierController::class, 'deleteBill'])->name('bill.delete')->middleware('permmission:07.08');
            Route::post('bill-item', [SupplierController::class, 'billItems'])->name('bill.item.list')->middleware('permmission:07.05');
            Route::get('bill-detail/{bill}', [SupplierController::class, 'billDetail'])->name('bill.item.detail')->middleware('permmission:07.05');
            Route::post('bill-cancel', [SupplierController::class, 'cancelBill'])->name('bill.item.cancel')->middleware('permmission:07.08');;

            // XXX supplier previous
            Route::get('previous-balance', [SupplierController::class, 'previousBalance'])->name('previous.balance')->middleware('permmission:07.10');
            Route::post('previous-balance-add', [SupplierController::class, 'previousBalanceAdd'])->name('previous.balance.add');
            Route::post('previous-balance-load', [SupplierController::class, 'previousBalanceLoad'])->name('previous.balance.load');
        });

        //xxx import
        Route::prefix('import')->name('import.')->group(function () {
            Route::match(['get', 'post'], 'supplier', [ImportController::class, 'supplier'])->name('supplier');
        });

        Route::prefix('employees')->name('employee.')->group(function () {
            // XXX XXX employees
            Route::get('', [EmployeeController::class, 'index'])->name('index')->middleware('permmission:05.01');
            Route::post('add', [EmployeeController::class, 'add'])->name('add');
            Route::post('update', [EmployeeController::class, 'update'])->name('update')->middleware('authority');
            Route::get('list', [EmployeeController::class, 'list'])->name('list');
            Route::post('delete', [EmployeeController::class, 'delete'])->name('delete')->middleware('authority');
            Route::get('detail/{id}', [EmployeeController::class, 'detail'])->name('detail');
            Route::post('load/emp/data', [EmployeeController::class, 'loadData'])->name('load.data');
            //XXX Employee Advance Management
            Route::middleware('permmission:05.06')->group(function () {

                Route::get('advance', [EmployeeController::class, 'advance'])->name('advance');
                Route::post('addadvance', [EmployeeController::class, 'addAdvance'])->name('advance.add');
                Route::post('getadvance', [EmployeeController::class, 'getAdvance'])->name('advance.list');
                Route::post('deladvance', [EmployeeController::class, 'delAdvance'])->name('advance.del')->middleware('authority');;
                Route::post('updateadvance', [EmployeeController::class, 'updateAdvance'])->name('advance.update')->middleware('authority');
                Route::post('editadvance', [EmployeeController::class, 'editAdvance'])->name('advance.edit')->middleware('authority');
                Route::post('advance/transfer', [EmployeeController::class, 'amountTransfer'])->name('amount.transfer');
            });

            //XXX Employee Sales Data
            Route::name('sales.')->prefix('sales')->group(function () {
                Route::match(["GET", "POST"], '', [EmployeeSalesController::class, 'index'])->name('index');
                Route::match(["POST"], 'save', [EmployeeSalesController::class, 'save'])->name('save');
                Route::match(["POST"], 'del', [EmployeeSalesController::class, 'del'])->name('del');
            });
            //XXX Employee Account Opening
            Route::match(['get', 'post'], 'account', [EmployeeController::class, 'accountIndex'])->name('account.index')->middleware('permmission:05.05');
            Route::match(['get', 'post'], 'account-add', [EmployeeController::class, 'accountAdd'])->name('account.add');
            //XXX Employee Month Closing
            Route::post('account-closing', [EmployeeController::class, 'closeSession'])->name('account.close');
            //XXX Employee Advance Management
            Route::middleware(['permmission:05.08'])->group(function () {
                Route::get('ret', [EmployeeController::class, 'ret'])->name('ret');
                Route::post('addret', [EmployeeController::class, 'addRet'])->name('ret.add');
                Route::post('getret', [EmployeeController::class, 'getRet'])->name('ret.list');
                Route::post('delret', [EmployeeController::class, 'delRet'])->name('ret.del');
                Route::post('updateret', [EmployeeController::class, 'updateRet'])->name('ret.update');
                Route::post('editret', [EmployeeController::class, 'editRet'])->name('ret.edit');
            });
        });

        // XXX sms
        Route::prefix('sms')->name('sms.')->group(function () {
            Route::post('distributer-credit', [SMSController::class, 'distributerCredit'])->name('distributer.credit');
            Route::post('customer-credit', [SMSController::class, 'customerCredit'])->name('customer.credit');
            Route::post('promo', [SMSController::class, 'promo'])->name('promo');
        });


        // XXX salary payment
        Route::prefix('employee/salary')->name('salary.')->group(function () {
            Route::get('/', 'Admin\EmployeeController@salaryIndex')->name('pay')->middleware('permmission:05.07');
            Route::post('load', 'Admin\EmployeeController@loadEmpData')->name('load.emp.data');
            Route::post('pay/salary', 'Admin\EmployeeController@storeSalary')->name('save');
            Route::post('list', 'Admin\EmployeeController@paidList')->name('list');
        });


        // XXX products
        Route::group(['prefix' => 'product'], function () {
            Route::name('product.')->group(function () {
                Route::get('', 'Admin\ProductController@index')->name('home');
                Route::post('add', 'Admin\ProductController@add')->name('add');
                Route::post('update', 'Admin\ProductController@update')->name('update')->middleware('authority');
                Route::post('del', 'Admin\ProductController@del')->name('del')->middleware('authority');
            });
        });

        Route::prefix('product/purchase')->name('purchase.')->group(function () {
            Route::get('', 'Admin\ProductController@productPurchase')->name('home');
            Route::post('', 'Admin\ProductController@productPurchaseStore')->name('store');
        });

        // manufacture
        // Route::group(['prefix' => 'manufacture'], function () {
        //     Route::name('manufacture.')->group(function () {
        //         Route::get('', 'Admin\ManufactureController@index')->name('index');
        //         Route::post('/store', 'Admin\ManufactureController@store')->name('store');
        //         Route::get('/list', 'Admin\ManufactureController@list')->name('list');
        //     });
        // });
        //XXX Manufacture Product
        Route::group(['prefix' => 'manufacture-product'], function () {
            Route::name('manufacture.product.')->middleware('permmission:13.01')->group(function () {
                Route::get('', [ManufactreProductController::class, 'index'])->name('index');
                Route::post('add', [ManufactreProductController::class, 'add'])->name('add');
                Route::post('del', [ManufactreProductController::class, 'del'])->name('del');
                Route::prefix('template')->name('template.')->group(function () {
                    Route::get('view/{id}', [ManufactreProductController::class, 'templateIndex'])->name('index');
                    Route::post('add/{id}', [ManufactreProductController::class, 'templateAdd'])->name('add');
                    Route::post('del', [ManufactreProductController::class, 'templateDel'])->name('del');
                    Route::post('update/{id}', [ManufactreProductController::class, 'templateUpdate'])->name('update');
                });
            });
        });

        Route::name('simple.manufacture.')->prefix('simple-manufacture')->middleware('permmission:13.06')->group(function () {
            Route::match(['get', 'post'], '', [SimpleManufactureController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'add', [SimpleManufactureController::class, 'add'])->name('add');
            Route::match(['get'], 'detail/{process}', [SimpleManufactureController::class, 'detail'])->name('detail');
            Route::match(['post'], 'cancel', [SimpleManufactureController::class, 'cancel'])->name('cancel');
        });

        //XXX item expire wastage
        Route::prefix('wastage')->name('wastage.')->middleware('permmission:03.09')->group(function () {
            Route::match(['get', 'post'], '', [WastageController::class, 'index'])->name('index');
            Route::match(['post'], 'add', [WastageController::class, 'add'])->name('add');
            Route::match(['post'], 'del', [WastageController::class, 'del'])->name('del');
        });


        //XXX maufacture process
        Route::prefix('manufacture-process')->name('manufacture.process.')->group(function () {
            Route::get('', [ManufactureProcessController::class, 'index'])->name('index')->middleware('permmission:13.02');

            Route::match(['get', 'post'], 'add', [ManufactureProcessController::class, 'add'])->name('add')->middleware('permmission:13.03');

            Route::middleware(['permmission:13.04'])->group(function () {
                Route::get('detail/{id}', [ManufactureProcessController::class, 'detail'])->name('detail');
                Route::match(['GET', 'POST'], 'edit/{id}', [ManufactureProcessController::class, 'edit'])->name('edit');
                Route::post('start-process/{id}', [ManufactureProcessController::class, 'startProcess'])->name('start.process');
                Route::post('finish-process/{id}', [ManufactureProcessController::class, 'finishProcess'])->name('finish.process');
                Route::post('cancel-process/{id}', [ManufactureProcessController::class, 'cancelProcess'])->name('cancel.process');
                Route::post('cancel-process-partial/{id}', [ManufactureProcessController::class, 'cancelProcessPartial'])->name('cancel.process.partial');
                Route::get('cancel-process-start/{id}', [ManufactureProcessController::class, 'cancelProcessStart'])->name('cancel.process.start');
            });

            Route::post('load-template', [ManufactureProcessController::class, 'loadTemplate'])->name('load.template');
            Route::post('check-stock', [ManufactureProcessController::class, 'checkStock'])->name('check.stock');
            Route::get('check-stock-saved/{id}', [ManufactureProcessController::class, 'checkStockSaved'])->name('check.stock.saved');
        });

        //XXX packaging
        Route::prefix('packaging')->name('packaging.')->group(function () {
            Route::match(['GET', 'POST'], '', [PackagingController::class, 'index'])->name('index');
            Route::match(['GET', 'POST'], 'add', [PackagingController::class, 'add'])->name('add');
            Route::match(['GET', 'POST'], 'edit', [PackagingController::class, 'edit'])->name('edit');
            Route::post('del', [PackagingController::class, 'del'])->name('del');
        });

        Route::get('xpay/{id}/{identifire}', [PaymentController::class, 'index'])->name('xpay');




        //XXX backup
        Route::group(['prefix' => 'backup'], function () {
            Route::name('backup.')->group(function () {
                Route::get('', [BackupController::class, 'index'])->name('index');
                Route::get('create', [BackupController::class, 'create'])->name('create');
                Route::post('del', [BackupController::class, 'del'])->name('del');
                Route::post('upload', [BackupController::class, 'del'])->name('del');
            });
        });


        //XXX accounting
        Route::prefix('accounting')->name('accounting.')->group(function () {
            //XXX Manage Stock
            Route::prefix('stock')->name('stock.')->group(function () {
                Route::match(['GET', 'POST'], '', [StockController::class, 'index'])->name('index');
                Route::post('add', [StockController::class, 'add'])->name('add');
                Route::post('del', [StockController::class, 'del'])->name('del');
            });
            //XXX Manage Bank Transactions
            Route::prefix('bank-transaction')->name('bank.transaction.')->group(function () {
                Route::match(['GET', 'POST'], '', [BankTransactionController::class, 'index'])->name('index');
                Route::match(['GET', 'POST'], 'add', [BankTransactionController::class, 'add'])->name('add');
                Route::post('cancel', [BankTransactionController::class, 'cancel'])->name('cancel');
            });
            //XXX Extra Income
            Route::prefix('extra-income')->name('extra.income.')->group(function () {
                Route::match(['get', 'post'], '', [ExtraIncomeController::class, 'index'])->name('index');
                Route::match(['get', 'post'], 'add', [ExtraIncomeController::class, 'add'])->name('add');
                Route::match(['get', 'post'], 'update/{id}', [ExtraIncomeController::class, 'update'])->name('update');
                Route::match(['get', 'post'], 'del', [ExtraIncomeController::class, 'del'])->name('del');
                //XXX Extra Income Cateories
                Route::match(['get', 'post'], 'category', [ExtraIncomeController::class, 'category'])->name('category');
                Route::match(['get', 'post'], 'category-add', [ExtraIncomeController::class, 'categoryAdd'])->name('category.add');
                Route::match(['get', 'post'], 'category-update', [ExtraIncomeController::class, 'categoryUpdate'])->name('category.update');
                Route::match(['get', 'post'], 'category-del/{id}', [ExtraIncomeController::class, 'categoryDel'])->name('category.del');
            });

            Route::get('', [AccountingController::class, 'index'])->name('index');

            // XXX Manage Accounts
            Route::prefix('accounts')->name('accounts.')->group(function () {
                Route::match(['GET', 'POST'], '', [AccountingController::class, 'accounts'])->name('index');
                Route::match(['GET', 'POST'], 'edit/{id}', [AccountingController::class, 'accountsEdit'])->name('edit');
                Route::match(['GET', 'POST'], 'ledger/{id}', [AccountingController::class, 'accountsLedger'])->name('ledger');
                Route::match(['GET', 'POST'], 'add/{type}/{parent_id}', [AccountingController::class, 'accountsAdd'])->name('add');
                // XXX Fixed assets
                Route::prefix('fixed-assets')->name('fixed.assets.')->group(function () {
                    Route::get('index/{account}', [FixedAssetController::class, 'index'])->name('index');
                    Route::match(['GET', 'POST'], 'add', [FixedAssetController::class, 'add'])->name('add');
                    Route::match(['GET', 'POST'], 'update/{id}', [FixedAssetController::class, 'update'])->name('update');
                    Route::match(['POST'], 'del', [FixedAssetController::class, 'del'])->name('del');
                    Route::prefix('categories')->name('categories.')->group(function () {
                        Route::get('', [FixedAssetController::class, 'CategoryIndex'])->name('index');
                        Route::post('add', [FixedAssetController::class, 'CategoryAdd'])->name('add');
                        Route::post('update', [FixedAssetController::class, 'CategoryUpdate'])->name('update');
                        Route::post('del', [FixedAssetController::class, 'CategoryDel'])->name('del');
                    });
                });
            });

            Route::get('subaccounts/{id}', [AccountingController::class, 'subAccounts'])->name('subaccounts');
            Route::match(['GET', 'POST'], 'final', [AccountingController::class, 'final'])->name('final');

            Route::prefix('opening')->name('opening.')->group(function () {
                Route::match(['get', 'post'], '', [AccountOpeningController::class, 'index'])->name('index');
            });
        });


        // XXX Ledgers
        Route::group(['prefix' => 'ledger'], function () {
            Route::name('ledger.')->group(function () {
                Route::match(['GET', 'POST'], 'update', 'LedgerController@update')->name('update')->middleware('authority');
                Route::match(['GET', 'POST'], 'edit', 'LedgerController@edit')->name('edit')->middleware('authority');
                Route::match(['GET', 'POST'], 'del', 'LedgerController@del')->name('del')->middleware('authority');

                // Route::match(['GET','POST'],'sellupdate','LedgerController@sellUpdate')->name('sellupdate')->middleware('authority');
                // Route::match(['GET','POST'],'payupdate','LedgerController@payUpdate')->name('payupdate')->middleware('authority');

                // Route::match(['GET','POST'],'del','LedgerController@del')->name('del')->middleware('authority');

                // Route::group(['prefix' => 'itemsell'], function () {
                //     Route::name('itemsell.')->group(function(){
                //         Route::match(['GET','POST'],'update','FarmerLedgerController@update')->name('update')->middleware('authority');
                //         Route::match(['GET','POST'],'sellupdate','FarmerLedgerController@sellUpdate')->name('sellupdate')->middleware('authority');
                //         Route::match(['GET','POST'],'selldel','FarmerLedgerController@sellDel')->name('selldel')->middleware('authority');
                //         Route::match(['GET','POST'],'payupdate','FarmerLedgerController@payUpdate')->name('payupdate')->middleware('authority');
                //         Route::match(['GET','POST'],'del','FarmerLedgerController@del')->name('del')->middleware('authority');
                //     });
                // });
            });
        });


        //XXX Customer
        Route::group(['prefix' => 'customer'], function () {
            Route::name('customer.')->group(function () {
                Route::match(['GET', 'POST'], '', [CustomerController::class, 'index'])->name('home')->middleware('permmission:08.01');
                Route::match(['GET', 'POST'], 'all', [CustomerController::class, 'all'])->name('all');
                Route::post('add', [CustomerController::class, 'add'])->name('add');
                Route::post('update', [CustomerController::class, 'update'])->name('update')->middleware('authority');
                Route::post('del', [CustomerController::class, 'del'])->name('del')->middleware('authority');

                //detail
                Route::match(['get', 'post'], 'detail/{id}', [CustomerController::class, 'detail'])->name('detail');

                Route::name('payment.')->middleware('permmission:08.02')->prefix('payment')->group(function () {
                    Route::match(['get', 'post'],  '', [CustomerController::class, 'payment'])->name('index');
                    Route::match(['get', 'post'],  'add', [CustomerController::class, 'addPayment'])->name('add');
                    Route::match(['get', 'post'],  'del', [CustomerController::class, 'delPayment'])->name('del');
                });
                Route::match(['GET', 'POST'], 'promo', [CustomerController::class, 'promo'])->name('promo');

                Route::name('credit-list.')->prefix('credit-list')->group(function () {
                    Route::match(['get', 'post'],  '', [CustomerController::class, 'creditList'])->name('index')->middleware('permmission:08.02');
                    Route::match(['get', 'post'],  'send-sms', [CustomerController::class, 'sendSMS'])->name('send-sms');
                });
            });
        });

        //XXX report routes
        Route::group(['prefix' => 'report'], function () {
            Route::name('report.')->group(function () {

                Route::get('', [ReportController::class, 'index'])->name('home')->middleware('permmission:12.01');
                Route::match(['GET', 'POST'], 'farmer', [ReportController::class, 'farmer'])->name('farmer');
                Route::post('farmer/changeSession', [ReportController::class, 'farmerSession'])->name('farmer.session');
                Route::post('farmer/single/changeSession', [ReportController::class, 'farmerSingleSession'])->name('farmer.single.session');
                Route::match(['GET', 'POST'], 'milk', [ReportController::class, 'milk'])->name('milk');
                Route::match(['GET', 'POST'], 'sales', [ReportController::class, 'sales'])->name('sales');
                Route::match(['GET', 'POST'], 'pos', [ReportController::class, 'posSales'])->name('pos.sales');
                Route::match(['GET', 'POST'], 'distributor', [ReportController::class, 'distributor'])->name('dis');
                Route::match(['GET', 'POST'], 'employee', [ReportController::class, 'employee'])->name('emp');
                Route::match(['GET', 'POST'], 'credit', [ReportController::class, 'credit'])->name('credit');
                Route::post('employee/changeSession', [ReportController::class, 'employeeSession'])->name('emp.session');
                Route::match(['GET', 'POST'], 'expenses', [ReportController::class, 'expense'])->name('expense');
                Route::match(['GET', 'POST'], 'bonus', [ReportController::class, 'bonus'])->name('bonus');
                Route::match(['GET', 'POST'], 'stock', [ReportController::class, 'stock'])->name('stock');
                Route::match(['GET', 'POST'], 'restaurant', [RestaurantReportController::class, 'index'])->name('restaurant');

                Route::match(['GET', 'POST'], 'farmer-with-milk', [FarmerReportController::class, 'farmerWithMilk'])->name('farmer.with.milk');


            });
        });

        //XXX summary
        Route::prefix('summary')->name('summary.')->group(function () {
            Route::match(['get', 'post'], '', [SummaryController::class, 'index'])->name('index');
        });

        ///XXX billing
        Route::group(['prefix' => 'offers'], function () {
            Route::name('offers.')->group(function () {
                Route::match(['get', 'post'], '', [OfferController::class, 'index'])->name('index')->middleware('permmission:10.03');
                Route::post('add', [OfferController::class, 'add'])->name('add');
                Route::post('update', [OfferController::class, 'update'])->name('update');
                Route::get('del/{offer}', [OfferController::class, 'del'])->name('del');
                Route::get('activate/{offer}', [OfferController::class, 'activate'])->name('activate');
                Route::get('detail/{offer}', [OfferController::class, 'detail'])->name('detail');
                Route::match(['get', 'post'], 'items', [OfferController::class, 'getItems'])->name('get-items');
            });
        });

        ///XXX billing
        Route::group(['prefix' => 'billing'], function () {
            Route::name('billing.')->group(function () {
                Route::middleware('permmission:15.01')->group(function () {

                    Route::get('', 'Billing\BillingController@index')->name('home');
                    Route::get('detail/{id}', 'Billing\BillingController@detail')->name('detail');
                    Route::post('del/{id}', 'Billing\BillingController@del')->name('del');
                    Route::post('save', 'Billing\BillingController@save')->name('save');
                });
                Route::match(['get', 'post'], "list", [BillingBillingController::class, 'list'])->name('list')->middleware('permmission:15.02');
            });
        });

        //XXX POS Billing
        Route::prefix('pos-billing')->group(function () {
            Route::name('pos.billing.')->group(function () {
                Route::match(['GET', 'POST'], "", [PosBillingController::class, 'index'])->name('index')->middleware('permmission:09.02');
                Route::match(['GET', 'POST'], "detail", [PosBillingController::class, 'detail'])->name('detail');
                Route::match(['GET', 'POST'], "print", [PosBillingController::class, 'print'])->name('print')->middleware('permmission:09.03');
                Route::match(['GET', 'POST'], "print-info", [PosBillingController::class, 'printInfo'])->name('print.info');
                Route::match(['GET', 'POST'], "creditnote-info", [PosBillingController::class, 'creditNoteInfo'])->name('creditnote.info');
                Route::match(['GET', 'POST'], "return", [PosBillingController::class, 'salesReturn'])->name('return')->middleware('permmission:09.04');
                Route::match(['GET', 'POST'], "return-single/{bill}", [PosBillingController::class, 'salesReturnSingle'])->name('return-single');
                Route::match(['GET', 'POST'], "cancel", [PosBillingController::class, 'cancel'])->name('cancel');
                Route::match(['GET', 'POST'], "return/init", [PosBillingController::class, 'initSalesReturn'])->name('return.init');
                Route::match(['GET', 'POST'], "return/print/{note}", [PosBillingController::class, 'printSalesReturn'])->name('return.print');
            });
        });

        //XXX Counter
        Route::group(['prefix' => 'counter'], function () {
            Route::name('counter.')->group(function () {
                Route::get('', [CounterController::class, 'index'])->name('home')->middleware('permmission:10.02');
                Route::post('list', [CounterController::class, 'list'])->name('list')->middleware('permmission:10.02');
                Route::get('stat/{counter}', [CounterController::class, 'getStatus'])->name('status.get');
                Route::post('update/{id}', [CounterController::class, 'update'])->name('update');
                Route::post('add', [CounterController::class, 'add'])->name('add');
                Route::get('delete/{id}', [CounterController::class, 'del'])->name('delete');
                Route::match(['get', 'post'], 'status/{id}', [CounterController::class, 'status'])->name('status');

                Route::group(['prefix' => 'day'], function () {
                    Route::name('day.')->group(function () {
                        Route::get('', [CounterController::class, 'day'])->name('index')->middleware('permmission:10.01');
                        Route::post('open', [CounterController::class, 'dayOpen'])->name('open');
                        Route::post('approve', [CounterController::class, 'dayApprove'])->name('approve');
                        Route::get('reopen', [CounterController::class, 'dayReopen'])->name('reopen');
                    });
                });
            });
        });

        //table management
        Route::prefix('table')->name("table.")->group(function () {
            route::match(['GET', 'POST'], '', [TableController::class, 'index'])->name('index');
            route::match(['GET', 'POST'], 'edit', [TableController::class, 'edit'])->name('edit');
            route::match(['GET', 'POST'], 'del/{id}', [TableController::class, 'del'])->name('del');
            route::match(['GET', 'POST'], 'add', [TableController::class, 'add'])->name('add');
            //table sections
            Route::prefix('section')->name("section.")->group(function () {
                route::match(['GET', 'POST'], '', [SectionController::class, 'index'])->name('index');
                route::match(['GET', 'POST'], 'edit', [SectionController::class, 'edit'])->name('edit');
                route::match(['GET', 'POST'], 'del/{id}', [SectionController::class, 'del'])->name('del');
                route::match(['GET', 'POST'], 'add', [SectionController::class, 'add'])->name('add');
            });
        });

        //xxx point calculation
        Route::prefix('point-calculation')->name("point.")->group(function () {
            route::match(['GET', 'POST'], '', [PointController::class, 'index'])->name('index');
        });

        Route::group(['prefix' => 'bank'], function () {
            Route::name('bank.')->middleware('permmission:11.01')->group(function () {
                Route::get('index/{account}', [BankController::class, 'index'])->name('index');
                Route::post('add', [BankController::class, 'add'])->name('add');
                Route::post('update', [BankController::class, 'update'])->name('update');
                Route::post('delete', [BankController::class, 'delete'])->name('delete');
            });
        });
        Route::group(['prefix' => 'gateway'], function () {
            Route::name('gateway.')->middleware('permmission:11.02')->group(function () {
                Route::get('', [GatewayController::class, 'index'])->name('index');
                Route::post('add', [GatewayController::class, 'add'])->name('add');
                Route::post('update', [GatewayController::class, 'update'])->name('update');
                Route::post('delete', [GatewayController::class, 'delete'])->name('delete');
            });
        });

        Route::prefix('cash-flow')->name('cash.flow.')->group(function () {
            Route::get('', 'Admin\CashflowController@index')->name('index');
            Route::post('data', 'Admin\CashflowController@data')->name('data');
        });

        Route::prefix('home-setting')->name('setting.')->group(function () {
            Route::match(['GET', 'POST'], 'about', 'Admin\HomepageController@abountus')->name('about');
            Route::match(['GET', 'POST'], 'sliders', 'Admin\HomepageController@sliders')->name('sliders');
            Route::match(['GET', 'POST'], 'slider/{id}', 'Admin\HomepageController@sliderDel')->name('slider.del');
            Route::match(['GET', 'POST'], 'gallery', 'Admin\HomepageController@gallery')->name('gallery');
            Route::match(['GET', 'POST'], 'gallery/del/{gallery}', 'Admin\HomepageController@galleryDel')->name('gallery-del');
        });

        Route::prefix('setting')->name('setting.')->group(function () {
            Route::prefix('conversion')->name('conversion.')->group(function () {

                Route::get('', [ConversionController::class, 'index'])->name('index');
                Route::post('add', [ConversionController::class, 'add'])->name('add');
                Route::post('add-sub', [ConversionController::class, 'addSub'])->name('add.sub');
                Route::post('update-sub', [ConversionController::class, 'updateSub'])->name('update.sub');
                Route::post('update', [ConversionController::class, 'update'])->name('update');
                Route::post('del', [ConversionController::class, 'del'])->name('del');
                // Route::get('dell', [ConversionController::class, 'del'])->name('dell');
            });
        });

        Route::group(['prefix' => 'user'], function () {
            Route::name('user.')->group(function () {
                Route::match(['GET', 'POST'], '', [UserController::class, 'index'])->name('users');
                Route::match(['GET', 'POST'], 'add', [UserController::class, 'userAdd'])->name('add');
                Route::match(['GET', 'POST'], 'delete/{id}', [UserController::class, 'delete'])->name('delete');
                Route::match(['GET', 'POST'], 'per/{user}', [UserController::class, 'per'])->name('per');
                Route::match(['GET', 'POST'], 'api-per/{user}', [UserController::class, 'perAPI'])->name('api.per');
                Route::match(['GET', 'POST'], 'update/{update}', [UserController::class, 'update'])->name('update')->middleware('authority');
                Route::match(['GET', 'POST'], 'change/password', [UserController::class, 'changePassword'])->name('change.password');
                Route::match(['GET', 'POST'], 'non-super-admin/change/password/{id}', [UserController::class, 'nonSuperadminChangePassword'])->name('non.super.admin.change.password');
            });
        });
        Route::name('barcode.')->prefix('barcode')->middleware('authority')->group(function () {
            Route::match(['GET', 'POST'], '', [BarcodeController::class, 'index'])->name('index');
        });

        Route::name('report.day.')->prefix('report/day')->group(function () {
            Route::match(['get', 'post'], 'milk', [DayReportController::class, 'milk'])->name('milk');
            Route::match(['get', 'post'], '', [DayReportController::class, 'index'])->name('index');
        });
    });
});

Route::name('restaurant.')->prefix('restaurant')->middleware('permmission:14.02')->group(function () {

    Route::match(['GET', 'POST'], 'table', [RestaurantController::class, 'table'])->name('table');
    Route::match(['GET', 'POST'], 'bill', [RestaurantController::class, 'bill'])->name('bill');
    Route::match(['GET', 'POST'], 'print', [RestaurantController::class, 'print'])->name('print');
    Route::match(['GET', 'POST'], 'checkLogin', [RestaurantController::class, 'checkLogin'])->name('checkLogin');
    Route::match(['GET', 'POST'], 'kot', [RestaurantController::class, 'kot'])->name('kot');
    Route::match(['POST'], 'kotDel', [RestaurantController::class, 'kotDel'])->name('kotDel');
});


Route::name('pos.')->prefix('pos')->group(function () {

    Route::middleware(['pos'])->group(function () {
        Route::view('day', 'pos.counter.day')->name('day')->middleware('permmission:09.01');
        Route::match(['GET', 'POST'], 'bill', [POSController::class, 'index'])->name('index');
        Route::match(['GET', 'POST'], 'counter', [POSController::class, 'counter'])->name('counter');
        Route::match(['GET', 'POST'], 'counter-open', [POSController::class, 'counterOpen'])->name('counter.open');
        Route::match(['GET', 'POST'], 'counter-another', [POSController::class, 'counterAnother'])->name('counter.another');
        Route::get('counter/status', [POSController::class, 'counterStatus'])->name('counterStatus');
        Route::get('items', [POSController::class, 'items'])->name('items');
        Route::post('items/single', [POSController::class, 'itemSingle'])->name('items-single');
        Route::get('counter/current', [POSController::class, 'counterCurrent'])->name('counter-current');
        Route::post('counter/close', [POSController::class, 'counterClose'])->name('counter-close');

        Route::get('customers', [POSController::class, 'customers'])->name('customers');
        Route::post('customers-add', [POSController::class, 'customersAdd'])->name('customers-add');
        Route::post('customer-search', [POSController::class, 'searchCustomer'])->name('customers-search');

        Route::name('billing.')->prefix('billing')->group(function () {
            Route::post('add', [BillingController::class, 'add'])->name('add');
            Route::post('hold', [BillingController::class, 'hold'])->name('hold');
            Route::get('hold-list', [BillingController::class, 'holdList'])->name('hold-list');
            Route::post('printed', [BillingController::class, 'printed'])->name('printed');
            Route::get('print/{bill}', [BillingController::class, 'print'])->name('print');
        });
    });
});


Route::group(['middleware' => 'role:farmer', 'prefix' => 'farmer'], function () {
    Route::name('farmer.')->group(function () {
        Route::get('home', 'Users\FarmerDashboardController@index')->name('dashboard');
        Route::post('change/password', 'Users\FarmerDashboardController@changePassword')->name('change.password');
        Route::get('transaction/detail', 'Users\FarmerDashboardController@transactionDetail')->name('milk.detail');
        Route::post('load-data', 'Users\FarmerDashboardController@loadData')->name('loaddata');
        Route::get('change-password', 'Users\FarmerDashboardController@changePasswordPage')->name('password.page');
    });
});


Route::group(['middleware' => 'role:distributer', 'prefix' => 'distributor'], function () {
    Route::name('distributer.')->group(function () {
        Route::get('home', 'Users\DistributorDashboardController@index')->name('dashboard');
        Route::post('change/password', 'Users\DistributorDashboardController@changePassword')->name('change.password');
        Route::get('transaction/detail', 'Users\DistributorDashboardController@transactionDetail')->name('transaction.detail');
        Route::post('load-data', 'Users\DistributorDashboardController@loaddata')->name('loaddata');
        Route::get('change-password', 'Users\DistributorDashboardController@changePasswordPage')->name('password.page');
        Route::get('make-a-request', 'Users\DistributorDashboardController@makeArequest')->name('request');
        Route::post('make-a-request-add', 'Users\DistributorDashboardController@makeArequestAdd')->name('request.add');
        Route::post('make-a-request-update', 'Users\DistributorDashboardController@makeArequestUpdate')->name('request.update');
        Route::get('make-a-request/{id}', 'Users\DistributorDashboardController@requestDelete')->name('request.delete');
    });
});

Route::name('sahakari.')->prefix('sahakari')->group(function () {
    Route::get('', [HomeController::class, 'index'])->name('home');
    Route::name('members.')->prefix('members')->group(function () {
        Route::match(['GET', 'POST'], '', [MemberController::class, 'index'])->name('index');

        Route::match(['GET', 'POST'], 'add', [MemberController::class, 'add'])->name('add');
    });
});
