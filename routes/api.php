<?php

use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Api\customerController;
use App\Http\Controllers\Api\GeneralController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\LoginController;
use App\Models\Customer;
use App\Models\PosBill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('bill/{id}', function ($id) {
    // if($re)
    $bill=PosBill::find($id);
    $bill->billitems;
    return response()->json($bill);
});
Route::middleware('auth')->get('/user', function (Request $request) {

});
Route::middleware(['auth:api'])->group(function () {
    Route::match(['GET','POST'],'items',[ItemController::class,'index']);
    Route::get('centers',function(){
        return response(json_encode([
            'centers'=>DB::table('centers')->get(['id','name']),
            'company'=>[
                'name'=>env('companyName'),
                'phone'=>env('companyphone'),
                'reg'=>env('companyRegNO'),
                'panvat'=>env('companyVATPAN'),
                'usetax'=>env('companyUseTax'),
                'billtitle'=>env('companyBillTitle'),
                'address'=>env('companyAddress')
            ],
            'counters'=>DB::table('counters')->get(['id','name','center_id'])
        ]));
    });
    Route::post('pos-user',[LoginController::class,'addPosUser']);

    Route::middleware('permmission:09.05')->group(function(){
        Route::prefix('customers')->group(function(){
            Route::get('customers/{center_id}',[customerController::class,'index']);
        });
        Route::post('customers/{center_id}',[GeneralController::class,'getCustomers']);

        Route::post('sync-bill', [ItemController::class,'syncBills']);
        Route::post('sync-ledger', [ItemController::class,'syncLedger']);
    });
});
Route::match(['GET',"POST"],'show-ledger', [ItemController::class,'showLedger']);

// Route::get('json/{table}',function($table){
//     return response(json_encode(DB::table($table)->first(),JSON_NUMERIC_CHECK|JSON_PRESERVE_ZERO_FRACTION));
// });




Route::post('login',[LoginController::class,'index']);
Route::post('login-remote',[LoginController::class,'loginRemote']);
Route::get('info',[GeneralController::class,'info']);
Route::get('test',function(){
    return response()->json(['status'=>true]);
});

Route::get('',function(){
   return "Welcome To NeedTechnosoft";
});


Route::get('send-sms/@{pass}',function($pass){
    if($pass=="Chhatra123"){
        Artisan::call('send:sms');
    }else{
        return response('Authenticaton Failed');
    }
});
