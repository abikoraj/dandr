<?php

namespace App\Http\Controllers;

use App\Models\BankTransaction;
use App\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BankTransactionController extends Controller
{
    public function index(Request $request)
    {
        if($request->getMethod()=="POST"){
            $query=DB::table('bank_transactions');
            $transactions=rangeSelector($request,$query)->get();
            return response()->json($transactions);
        }else{
            $banks=getBanks();
            return view('admin.accounting.bank.transaction.index',compact('banks'));
        }
        
    }

    public function add(Request $request)
    {
        if($request->getMethod()=="POST"){
            $date=getNepaliDate($request->date);
            $transaction=new BankTransaction();
            $transaction->type=$request->type;
            if($transaction->type==1){
                $transaction->from_bank_id=$request->from_bank_id;
            }else if($transaction->type==2){
                $transaction->to_bank_id=$request->from_bank_id;

            }else if($transaction->type==3){
                $transaction->from_bank_id=$request->from_bank_id;
                $transaction->to_bank_id=$request->to_bank_id;

            }

            $transaction->date=$date;
            $transaction->number=$request->number;
            $transaction->amount=$request->amount;
            $transaction->transaction_by=$request->transaction_by;
            $transaction->remarks=$request->remarks;
            $transaction->save();

            $from_bank=DB::table('banks')->where('id',$request->from_bank_id)->first(['account_id','name']);
            $to_bank=DB::table('banks')->where('id',$request->to_bank_id)->first(['account_id','name']);
            if($transaction->type==1){
                pushCASH(2,$transaction->amount,801,$date,'To '.$from_bank->name.' A/C',$transaction->id );
                pushAccountLedger($from_bank->account_id,1,$transaction->amount,801,$date,'By Cash A/C',$transaction->id );
            }else if($transaction->type==2){
                pushCASH(1,$transaction->amount,802,$date,'By '.$from_bank->name.' A/C',$transaction->id );
                pushAccountLedger($from_bank->account_id,2,$transaction->amount,802,$date,'To Cash A/C',$transaction->id );
            }
            else if($transaction->type==3){
                pushAccountLedger($from_bank->account_id,1,$transaction->amount,803,$date,'By '.$to_bank->name.' A/C',$transaction->id );
                pushAccountLedger($to_bank->account_id,2,$transaction->amount,803,$date,'To '.$from_bank->name.' A/C',$transaction->id );
            }
        }else{
            $banks=getBanks();
            return view('admin.accounting.bank.transaction.add',compact('banks'));
        }
    }

    public function cancel(Request $request)
    {
        $type=DB::table('bank_transactions')->where('id',$request->id)->first(['type'])->type;
        DB::delete('delete from bank_transactions where id = ?', [$request->id]);
        PaymentManager::remove($request->id,(800+$type));
      
    }
}
