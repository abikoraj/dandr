<?php

use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CounterController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DistributerMilkController;
use App\Http\Controllers\Admin\DistributersellController;
use App\Http\Controllers\Admin\DistributerSnfFatController;
use App\Http\Controllers\Admin\GatewayController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\PosBillingController;
use App\Http\Controllers\Admin\SellitemController;
use App\Http\Controllers\POS\BillingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Sahakari\HomeController;
use App\Http\Controllers\Sahakari\Member\MemberController;
use App\Models\Item;
use App\Models\Sahakari\HomeCntroller;

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


Route::get('/', 'HomeController@home');
Route::get('/test/{id}', 'TestController@index')->name('test');
Route::get('/test-all/{id}', 'TestController@all')->name('test-all');
Route::get('/test-distributor', 'TestController@distributor')->name('test-distributor');
Route::get('/test-distributor-date', 'TestController@distributorByDate')->name('test-distributor');


Route::get('/pass', function () {
    // $pass = bcrypt('admin');
    // dd($pass);
    $id=1;
    $data= sprintf("%'.09d",$id);
    $arr=str_split($data,3);
    echo implode('/',$arr);
    
});




Route::match(['get', 'post'], 'login', 'AuthController@login')->name('login');
Route::match(['get', 'post'], 'logout', 'AuthController@logout')->name('logout');

Route::name('admin.')->group(function () {


    Route::group(['middleware' => 'role:admin', 'prefix' => 'admin'], function () {
        Route::get('dashboard', 'Admin\DashboardController@index')->name('dashboard');
        Route::prefix('collection-centers')->name('center.')->group(function () {
            // XXX collection centers
            Route::get('', 'Admin\CenterController@index')->name('index');
            Route::post('add', 'Admin\CenterController@addCollectionCenter')->name('add');
            Route::get('collection-center-list', 'Admin\CenterController@listCenter')->name('list.center');
            Route::post('delete', 'Admin\CenterController@deleteCenter')->name('delete')->middleware('authority');
            Route::post('update', 'Admin\CenterController@updateCollectionCenter')->name('update')->middleware('authority');
        });

        Route::prefix('farmers')->name('farmer.')->group(function () {
            // XXX farmer routes
            Route::get('', 'Admin\FarmerController@index')->name('list');
            Route::post('list-by-center', 'Admin\FarmerController@listFarmerByCenter')->name('list-bycenter');
            Route::post('minlist-by-center', 'Admin\FarmerController@minlistFarmerByCenter')->name('minlist-bycenter');


            Route::post('add', 'Admin\FarmerController@addFarmer')->name('add');
            Route::get('detail/{id}', 'Admin\FarmerController@farmerDetail')->name('detail');

            Route::post('update', 'Admin\FarmerController@updateFarmer')->name('update')->middleware('authority');
            Route::get('delete/{id}', 'Admin\FarmerController@deleteFarmer')->name('delete')->middleware('authority');
            Route::post('load-session-data', 'Admin\FarmerController@loadSessionData')->name('load-session-data');

            // XXX farmer due payments
            Route::get('due', 'Admin\FarmerController@due')->name('due');
            Route::post('due-load', 'Admin\FarmerController@dueLoad')->name('due.load');
            Route::post('pay-save', 'Admin\FarmerController@paymentSave')->name('pay.save');

            //XXX farmer Account opening 
            
            Route::match(['GET', 'POST'], 'add-due-list', 'Admin\FarmerController@addDueList')->name('due.add.list');
            Route::match(['GET', 'POST'], 'add-due', 'Admin\FarmerController@addDue')->name('due.add');

            // XXX  farmer advance
            Route::get('advances', 'Admin\AdvanceController@index')->name('advance');
            Route::post('advance-add', 'Admin\AdvanceController@add')->name('advance.add');
            Route::post('advance-list', 'Admin\AdvanceController@list')->name('advance.list');
            // Route::post('advance-update', 'Admin\AdvanceController@update')->name('advance.update')->middleware('authority');
            Route::post('advance-delete', 'Admin\AdvanceController@delete')->name('advance.delete')->middleware('authority');
            Route::post('advance-update', 'Admin\AdvanceController@update')->name('advance.update')->middleware('authority');
            Route::post('advance-list-by-date', 'Admin\AdvanceController@listByDate')->name('advance.list-by-date');
            // XXX Milk payment
            Route::group(['prefix' => 'milk-payment'], function () {
                Route::name('milk.payment.')->group(function () {
                    Route::match(['GET', 'POST'], '', 'Admin\MilkPaymentController@index')->name('index');
                    // Route::post('load','Admin\ProductController@index')->name('load');
                    Route::post('add', 'Admin\MilkPaymentController@add')->name('add');
                    Route::post('update', 'Admin\MilkPaymentController@update')->name('update')->middleware('authority');
                    Route::post('delete', 'Admin\MilkPaymentController@delete')->name('delete')->middleware('authority');
                    // Route::post('del','Admin\ProductController@del')->name('del')->middleware('authority');
                });
            });
        });

        Route::prefix('snf-fat')->name('snf-fat.')->group(function () {

            // XXX snf and fats
            Route::get('', 'Admin\SnffatController@index')->name('index');
            Route::post('load-data', 'Admin\SnffatController@snffatDataLoad')->name('load-data');
            Route::post('snf-fats-save', 'Admin\SnffatController@saveSnffatData')->name('store');
            Route::post('snf-fats-update', 'Admin\SnffatController@update')->name('update')->middleware('authority');
            Route::post('snf-fats-delete', 'Admin\SnffatController@delete')->name('delete')->middleware('authority');
        });

        Route::prefix('milk-data')->name('milk.')->group(function () {
            // XXX milk data
            Route::get('', 'Admin\MilkController@index')->name('index');
            Route::post('save/{type}', 'Admin\MilkController@saveMilkData')->name('store');
            Route::post('load', 'Admin\MilkController@milkDataLoad')->name('load');

            Route::post('update', 'Admin\MilkController@update')->name('update')->middleware('authority');
            Route::post('delete', 'Admin\MilkController@delete')->name('delete')->middleware('authority');

            Route::post('farmer-data-load', 'Admin\MilkController@loadFarmerData')->name('load.farmer.data');
        });

        Route::prefix('distributers')->name('distributer.')->group(function () {
            // XXX distributer
            Route::get('', 'Admin\DistributerController@index')->name('index');
            Route::post('add', 'Admin\DistributerController@add')->name('add');
            Route::get('list', 'Admin\DistributerController@list')->name('list');
            Route::post('update', 'Admin\DistributerController@update')->name('update');
            Route::post('delete', 'Admin\DistributerController@delete')->name('delete')->middleware('authority');

            Route::get('detail/{id}', 'Admin\DistributerController@distributerDetail')->name('detail');
            Route::post('detail', 'Admin\DistributerController@distributerDetailLoad')->name('detail.load');


            Route::get('opening', 'Admin\DistributerController@opening')->name('detail.opening');
            Route::post('opening/list', 'Admin\DistributerController@loadLedger')->name('detail.opening.list');
            Route::post('ledger', 'Admin\DistributerController@ledger')->name('detail.ledger');
            Route::post('ledger/update', 'Admin\DistributerController@updateLedger')->name('detail.ledger.update')->middleware('authority');

            // distributer request
            Route::get('request', 'Admin\DistributerController@distributerRequest')->name('request');
            Route::get('change/status/{id}', 'Admin\DistributerController@distributerRequestChangeStatus')->name('request.change.status');

            Route::match(['GET','POST'],'credit-list', 'Admin\DistributerController@creditList')->name('credit.list');



            // XXX distributer sell

            Route::get('sells', [DistributersellController::class,'index'])->name('sell');
            Route::post('sell-add', [DistributersellController::class,'addDistributersell'])->name('sell.add');
            Route::post('sell-list', [DistributersellController::class,'listDistributersell'])->name('sell.list');
            Route::post('sell-del', [DistributersellController::class,'deleteDistributersell'])->name('sell.del')->middleware('authority');

            //XXX Distributor Payments
            Route::get('payment', 'Admin\DistributorPaymentController@index')->name('payemnt');
            Route::post('due-list', 'Admin\DistributorPaymentController@due')->name('due');
            Route::post('due-pay', 'Admin\DistributorPaymentController@pay')->name('pay');

            //XXXX Distributer Milk Data
            Route::prefix('milk-data')->name('MilkData.')->group(function () {
                Route::match(['get', 'post'], '',[DistributerMilkController::class,'index'])->name('index');
                Route::match(['get', 'post'], 'add',[DistributerMilkController::class,'add'])->name('add');
                Route::match(['get', 'post'], 'update',[DistributerMilkController::class,'update'])->name('update');
                Route::match(['get', 'post'], 'delete',[DistributerMilkController::class,'delete'])->name('delete');
                Route::match(['get', 'post'], 'add-to-ledger',[DistributerMilkController::class,'addToLedger'])->name('addToLedger');
            });
            //XXXX Distributer Milk Data
            Route::prefix('snf-fat')->name('snffat.')->group(function () {
                Route::match(['get', 'post'], '',[DistributerSnfFatController::class,'index'])->name('index');
                Route::match(['get', 'post'], 'add',[DistributerSnfFatController::class,'add'])->name('add');
                Route::match(['get', 'post'], 'update',[DistributerSnfFatController::class,'update'])->name('update');
                Route::match(['get', 'post'], 'delete',[DistributerSnfFatController::class,'delete'])->name('delete');
            });
            
        });
        Route::prefix('categories')->name('category.')->group(function () {
            // XXX items
            Route::match(['GET','POST'],'', [CategoryController::class,'index'])->name('index');
            Route::post('edit', [CategoryController::class,'edit'])->name('edit');
            Route::post('add', [CategoryController::class,'save'])->name('save');
            Route::get('delete/{cat}', [CategoryController::class,'delete'])->name('delete')->middleware('authority');

        });

        Route::prefix('items')->name('item.')->group(function () {
            // XXX items
            Route::match(['GET','POST'],'', [ItemController::class,'index'])->name('index');
            Route::match(['GET','POST'],'barcode', [ItemController::class,'barcode'])->name('barcode');
            Route::match(['GET','POST'],'product', [ItemController::class,'product'])->name('product');
            Route::match(['GET','POST'],'product-barcode', [ItemController::class,'productBarcode'])->name('product-barcode');
            Route::post('edit', [ItemController::class,'edit'])->name('edit');
            Route::post('add', [ItemController::class,'save'])->name('save');
            Route::match(['GET','POST'],'item-center-stock/{id}', [ItemController::class,'centerStock'])->name('center-stock');
            Route::get('item-delete/{id}', [ItemController::class,'delete'])->name('delete')->middleware('authority');
            Route::post('item-update', [ItemController::class,'update'])->name('update')->middleware('authority');
        });

        Route::prefix('sell-items')->name('sell.item.')->group(function () {

            // XXX sell items
            Route::get('', [SellitemController::class,'index'])->name('index');
            Route::post('add', [SellitemController::class,'addSellItem'])->name('add');
            Route::post('list', [SellitemController::class,'sellItemList'])->name('list');
            Route::post('update', [SellitemController::class,'updateSellItem'])->name('update');
            Route::post('delete', [SellitemController::class,'deleteSellitem'])->name('delete')->middleware('authority');
            Route::post('delete-all', [SellitemController::class,'multidel'])->name('del-all-selitem')->middleware('authority');
        });


        Route::prefix('expenses')->name('expense.')->group(function () {
            //XXX expense categories
            Route::get('categories', 'Admin\ExpenseController@categoryIndex')->name('category');
            Route::post('category-add', 'Admin\ExpenseController@categoryAdd')->name('category.add');
            Route::post('category-update', 'Admin\ExpenseController@categoryUpdate')->name('category.update');
            Route::post('category/expenses', 'Admin\ExpenseController@categoryExpenses')->name('list-category');

            // XXX  expensess
            Route::get('', 'Admin\ExpenseController@index')->name('index');
            Route::post('expense-add', 'Admin\ExpenseController@add')->name('add');
            Route::post('update', 'Admin\ExpenseController@update')->name('update')->middleware('authority');
            Route::post('edit', 'Admin\ExpenseController@edit')->name('edit')->middleware('authority');
            Route::get('list', 'Admin\ExpenseController@list')->name('list');
            Route::post('delete', 'Admin\ExpenseController@delete')->name('delete')->middleware('authority');
            Route::post('load', 'Admin\ExpenseController@load')->name('load');
        });


        Route::prefix('suppliers')->name('supplier.')->group(function () {
            // XXX suppliers
            Route::get('', 'Admin\SupplierController@index')->name('index');
            Route::post('add', 'Admin\SupplierController@add')->name('add');
            Route::get('list', 'Admin\SupplierController@list')->name('list');
            Route::post('delete', 'Admin\SupplierController@delete')->name('delete')->middleware('authority');
            Route::post('update', 'Admin\SupplierController@update')->name('update');
            //XXX supplier details
            Route::get('detail/{id}', 'Admin\SupplierController@detail')->name('detail');
            Route::post('load-detail', 'Admin\SupplierController@loadDetail')->name('load-detail');
            Route::get('payment', 'Admin\SupplierController@payment')->name('pay');
            Route::post('due', 'Admin\SupplierController@due')->name('due');
            Route::post('due-pay', 'Admin\SupplierController@duePay')->name('due.pay');

            // XXX supplier bills
            Route::get('bills', 'Admin\SupplierController@indexBill')->name('bill');
            Route::match(['GET','POST'],'bill-add', 'Admin\SupplierController@addBill')->name('bill.add');
            Route::post('bill-list', 'Admin\SupplierController@listBill')->name('bill.list');
            Route::post('bill-update', 'Admin\SupplierController@updateBill')->name('bill.update');
            Route::get('bill-delete', 'Admin\SupplierController@deleteBill')->name('bill.delete')->middleware('authority');
            Route::post('bill-item', 'Admin\SupplierController@billItems')->name('bill.item.list');
            Route::get('bill-detail/{bill}', 'Admin\SupplierController@billDetail')->name('bill.item.detail');
            Route::post('bill-cancel', 'Admin\SupplierController@cancelBill')->name('bill.item.cancel');

            // XXX supplier previous
            Route::get('previous-balance', 'Admin\SupplierController@previousBalance')->name('previous.balance');
            Route::post('previous-balance-add', 'Admin\SupplierController@previousBalanceAdd')->name('previous.balance.add');
            Route::post('previous-balance-load', 'Admin\SupplierController@previousBalanceLoad')->name('previous.balance.load');
        });

        Route::prefix('employees')->name('employee.')->group(function () {
            // XXX XXX employees
            Route::get('', 'Admin\EmployeeController@index')->name('index');
            Route::post('add', 'Admin\EmployeeController@add')->name('add');
            Route::post('update', 'Admin\EmployeeController@update')->name('update')->middleware('authority');
            Route::get('list', 'Admin\EmployeeController@list')->name('list');
            Route::post('delete', 'Admin\EmployeeController@delete')->name('delete')->middleware('authority');
            Route::get('detail/{id}', 'Admin\EmployeeController@detail')->name('detail');
            Route::post('load/emp/data', 'Admin\EmployeeController@loadData')->name('load.data');
            //XXX Employee Advance Management
            Route::get('advance', 'Admin\EmployeeController@advance')->name('advance');
            Route::post('addadvance', 'Admin\EmployeeController@addAdvance')->name('advance.add');
            Route::post('getadvance', 'Admin\EmployeeController@getAdvance')->name('advance.list');
            Route::post('deladvance', 'Admin\EmployeeController@delAdvance')->name('advance.del')->middleware('authority');;
            Route::post('updateadvance', 'Admin\EmployeeController@updateAdvance')->name('advance.update')->middleware('authority');
            Route::post('advance/transfer', 'Admin\EmployeeController@amountTransfer')->name('amount.transfer');
            //XXX Employee Account Opening
            Route::match(['get', 'post'],'account', 'Admin\EmployeeController@accountIndex')->name('account.index');
            Route::match(['get', 'post'],'account-add', 'Admin\EmployeeController@accountAdd')->name('account.add');
            //XXX Employee Month Closing
            Route::post('account-closing', 'Admin\EmployeeController@closeSession')->name('account.close');

        });


        Route::prefix('sms')->name('sms.')->group(function(){
            Route::post('distributer-credit','SMSController@distributerCredit')->name('distributer.credit');
        });








        // XXX salary payment
        Route::prefix('employee/salary')->name('salary.')->group(function () {
            Route::get('/', 'Admin\EmployeeController@salaryIndex')->name('pay');
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
        Route::group(['prefix' => 'manufacture'], function () {
            Route::name('manufacture.')->group(function () {
                Route::get('', 'Admin\ManufactureController@index')->name('index');
                Route::post('/store', 'Admin\ManufactureController@store')->name('store');
                Route::get('/list', 'Admin\ManufactureController@list')->name('list');
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
                Route::match(['GET','POST'],'', [CustomerController::class,'index'])->name('home');
                Route::post('add', [CustomerController::class,'add'])->name('add');
                Route::post('update', [CustomerController::class,'update'])->name('update')->middleware('authority');
                Route::post('del', [CustomerController::class,'del'])->name('del')->middleware('authority');

                //detail
                Route::match(['get', 'post'], 'detail/{id}',[CustomerController::class,'detail'])->name('detail');

                Route::name('payment.')->prefix('payment')->group(function(){
                    Route::match(['get', 'post'],  '',[CustomerController::class,'payment'])->name('index');
                    Route::match(['get', 'post'],  'add',[CustomerController::class,'addPayment'])->name('add');
                });
            });
        });

        //XXX report routes

        Route::group(['prefix' => 'report'], function () {
            Route::name('report.')->group(function () {

                Route::get('', 'ReportController@index')->name('home');

                Route::match(['GET', 'POST'], 'farmer', 'ReportController@farmer')->name('farmer');
                Route::post('farmer/changeSession', 'ReportController@farmerSession')->name('farmer.session');
                Route::post('farmer/single/changeSession', 'ReportController@farmerSingleSession')->name('farmer.single.session');

                Route::match(['GET', 'POST'], 'milk', 'ReportController@milk')->name('milk');
                Route::match(['GET', 'POST'], 'sales', 'ReportController@sales')->name('sales');
                Route::match(['GET', 'POST'], 'pos', 'ReportController@posSales')->name('pos.sales');
                Route::match(['GET', 'POST'], 'distributor', 'ReportController@distributor')->name('dis');
                Route::match(['GET', 'POST'], 'employee', 'ReportController@employee')->name('emp');
                Route::match(['GET', 'POST'], 'credit', 'ReportController@credit')->name('credit');
                Route::post('employee/changeSession', 'ReportController@employeeSession')->name('emp.session');
                Route::match(['GET', 'POST'], 'expenses', 'ReportController@expense')->name('expense');
            });
        });

        ///XXX billing
        Route::group(['prefix' => 'offers'], function () {
            Route::name('offers.')->group(function () {
                Route::match(['get', 'post'], '',[OfferController::class,'index'])->name('index');
                Route::post( 'add',[OfferController::class,'add'])->name('add');
                Route::post( 'update',[OfferController::class,'update'])->name('update');
                Route::get( 'del/{offer}',[OfferController::class,'del'])->name('del');
                Route::get( 'activate/{offer}',[OfferController::class,'activate'])->name('activate');
                Route::get( 'detail/{offer}',[OfferController::class,'detail'])->name('detail');
                Route::match(['get', 'post'], 'items',[OfferController::class,'getItems'])->name('get-items');
            });
        });

        ///XXX billing
        Route::group(['prefix' => 'billing'], function () {
            Route::name('billing.')->group(function () {
                Route::get('', 'Billing\BillingController@index')->name('home');
                Route::get('detail/{id}', 'Billing\BillingController@detail')->name('detail');
                Route::post('save', 'Billing\BillingController@save')->name('save');
            });
        });

        //XXX POS Billing
        Route::prefix('pos-billing')->group(function () {
            Route::name('pos.billing.')->group(function () {
                Route::match(['GET','POST'], "",[PosBillingController::class,'index'])->name('index');
                Route::match(['GET','POST'], "detail",[PosBillingController::class,'detail'])->name('detail');
                Route::match(['GET','POST'], "print",[PosBillingController::class,'print'])->name('print');
                Route::match(['GET','POST'], "print-info",[PosBillingController::class,'printInfo'])->name('print.info');
                
                Route::match(['GET','POST'], "return",[PosBillingController::class,'salesReturn'])->name('return');
                Route::match(['GET','POST'], "cancel",[PosBillingController::class,'cancel'])->name('cancel');
            });
        });

        //XXX Counter
        Route::group(['prefix' => 'counter'], function () {
            Route::name('counter.')->group(function () {
                Route::get('', 'Admin\CounterController@index')->name('home');
                Route::get('stat/{counter}', [CounterController::class,'getStatus'])->name('status.get');
                Route::post('update/{id}', 'Admin\CounterController@update')->name('update');
                Route::post('add', 'Admin\CounterController@add')->name('add');
                Route::post('delete/{id}', 'Admin\CounterController@delete')->name('delete');
                Route::match(['get', 'post'], 'status/{id}','Admin\CounterController@status')->name('status');

                Route::group(['prefix' => 'day'], function () {
                    Route::name('day.')->group(function () {
                        Route::get('', 'Admin\CounterController@day')->name('index');
                        Route::post('open', 'Admin\CounterController@dayOpen')->name('open');
                        Route::post('approve', 'Admin\CounterController@dayApprove')->name('approve');
                    });
                });

            });
        });

        Route::group(['prefix' => 'bank'], function () {
            Route::name('bank.')->group(function () {
                Route::get('', [BankController::class,'index'])->name('index');
                Route::post('add', [BankController::class,'add'])->name('add');
                Route::post('update', [BankController::class,'update'])->name('update');
                Route::post('delete', [BankController::class,'delete'])->name('delete');
            });
        });
        Route::group(['prefix' => 'gateway'], function () {
            Route::name('gateway.')->group(function () {
                Route::get('', [GatewayController::class,'index'])->name('index');
                Route::post('add', [GatewayController::class,'add'])->name('add');
                Route::post('update', [GatewayController::class,'update'])->name('update');
                Route::post('delete', [GatewayController::class,'delete'])->name('delete');
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


        Route::group(['prefix' => 'user'], function () {
            Route::name('user.')->group(function () {
                Route::match(['GET', 'POST'], '', 'Admin\UserController@index')->name('users');
                Route::match(['GET', 'POST'], 'add', 'Admin\UserController@userAdd')->name('add');
                Route::match(['GET', 'POST'], 'delete/{id}', 'Admin\UserController@delete')->name('delete');
                Route::match(['GET', 'POST'], 'update/{update}', 'Admin\UserController@update')->name('update')->middleware('authority');
                Route::match(['GET', 'POST'], 'change/password', 'Admin\UserController@changePassword')->name('change.password');
                Route::match(['GET', 'POST'], 'non-super-admin/change/password/{id}', 'Admin\UserController@nonSuperadminChangePassword')->name('non.super.admin.change.password');
            });
        });


    });

});


Route::name('pos.')->prefix('pos')->group(function(){
    
    Route::middleware(['pos'])->group(function () {
        Route::view('day', 'pos.counter.day')->name('day');
        Route::match(['GET', 'POST'], 'bill', 'POS\POSController@index')->name('index');
        Route::match(['GET', 'POST'], 'counter', 'POS\POSController@counter')->name('counter');
        Route::match(['GET', 'POST'], 'counter-open', 'POS\POSController@counterOpen')->name('counter.open');
        Route::match(['GET', 'POST'], 'counter-another', 'POS\POSController@counterAnother')->name('counter.another');
        Route::get('counter/status', 'POS\POSController@counterStatus')->name('counterStatus');
        Route::get('items', 'POS\POSController@items')->name('items');
        
        Route::get('customers', 'POS\POSController@customers')->name('customers');
        Route::post('customers-add', 'POS\POSController@customersAdd')->name('customers-add');
        Route::post('customer-search', 'POS\POSController@searchCustomer')->name('customers-search');
        Route::name('billing.')->prefix('billing')->group(function(){
            Route::post('add', [BillingController::class,'add'])->name('add');
            Route::post('printed', [BillingController::class,'printed'])->name('printed');
            Route::get('print/{bill}', [BillingController::class,'print'])->name('print');
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

Route::name('sahakari.')->prefix('sahakari')->group(function(){
    Route::get('',[HomeController::class,'index'])->name('home');
    Route::name('members.')->prefix('members')->group(function(){
        Route::match(['GET','POST'],'',[MemberController::class,'index'])->name('index');

        Route::match(['GET','POST'],'add',[MemberController::class,'add'])->name('add');
    });
});