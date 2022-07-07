<?php

namespace App\Http\Middleware;

use App\PaymentManager;
use Closure;
use Illuminate\Http\Request;

class PaymentInceptor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->getMethod() == "POST") {
            if ($request->filled('xpay')) {
                $amount = $request->input('xpay_amount');
                if ($amount > 0) {
                    $method = $request->input('xpay_method');
                    $type = $request->input('xpay');

                    $paymentManager = new PaymentManager();
                    if ($method == 1) {
                        if($type==1){
                            $paymentManager->receiveCash($amount);
                        }
                    } else if ($method == 2) {
                        $bank_id=$request->input('xpay_bank');
                        if($type==1){
                            $paymentManager->receiveBank($bank_id,$amount);
                        }
                    } else if ($method == 3) {
                        $cashAmount=$request->input('xpay_custom_cash')??0;
                        if($cashAmount>0){
                            if($type==1){
                                $paymentManager->receiveCash($cashAmount);
                            }
                        }
                        if($request->filled('xpay_custom_bank')){
                            $bank_ids=$request->xpay_custom_bank;
                            foreach ($bank_ids as $key => $bank_id) {
                                $bankAmount=$request->input('xpay_custom_bank_amount_'.$bank_id);
                                if($type==1){
                                    $paymentManager->receiveBank($bank_id,$bankAmount);
                                }
                            }
                        }
                    }
                    // $payment=(object)[
                    //     "method"=>$request->input('xpay_method'),
                    //     "amount"=>$request->input('xpay_amount'),
                    //     "bank"=>$request->input('xpay_bank'),
                    //     "bank"=>$request->input('xpay_bank'),

                    // ];
                    // dd($payment);
                }
            }
        }
        return $next($request);
    }
}
