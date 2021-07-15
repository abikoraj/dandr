<?php

use Illuminate\Support\Facades\Route;


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
    $pass = bcrypt('admin');
    dd($pass);
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



            // XXX distributer sell

            Route::get('sells', 'Admin\DistributersellController@index')->name('sell');
            Route::post('sell-add', 'Admin\DistributersellController@addDistributersell')->name('sell.add');
            Route::post('sell-list', 'Admin\DistributersellController@listDistributersell')->name('sell.list');
            Route::post('sell-del', 'Admin\DistributersellController@deleteDistributersell')->name('sell.del')->middleware('authority');

            //XXX Distributor Payments
            Route::get('payment', 'Admin\DistributorPaymentController@index')->name('payemnt');
            Route::post('due-list', 'Admin\DistributorPaymentController@due')->name('due');
            Route::post('due-pay', 'Admin\DistributorPaymentController@pay')->name('pay');
        });

        Route::prefix('items')->name('item.')->group(function () {
            // XXX items
            Route::get('', 'Admin\ItemController@index')->name('index');
            Route::post('edit', 'Admin\ItemController@edit')->name('edit');
            Route::post('add', 'Admin\ItemController@save')->name('save');
            Route::match(['GET','POST'],'item-center-stock/{id}', 'Admin\ItemController@centerStock')->name('center-stock');
            Route::get('item-delete/{id}', 'Admin\ItemController@delete')->name('delete')->middleware('authority');
            Route::post('item-update', 'Admin\ItemController@update')->middleware('authority');
        });

        Route::prefix('sell-items')->name('sell.item.')->group(function () {

            // XXX sell items
            Route::get('', 'Admin\SellitemController@index')->name('index');
            Route::post('add', 'Admin\SellitemController@addSellItem')->name('add');
            Route::post('list', 'Admin\SellitemController@sellItemList')->name('list');
            Route::post('update', 'Admin\SellitemController@updateSellItem')->name('update');
            Route::post('delete', 'Admin\SellitemController@deleteSellitem')->name('delete')->middleware('authority');
            Route::post('delete-all', 'Admin\SellitemController@multidel')->name('del-all-selitem')->middleware('authority');
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

        Route::group(['prefix' => 'billing'], function () {
            Route::name('billing.')->group(function () {
                Route::get('', 'Billing\BillingController@index')->name('home');
                Route::post('save', 'Billing\BillingController@save')->name('save');
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
