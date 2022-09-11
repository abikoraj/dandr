<?php

use App\Models\Account;
use App\Models\AccountLedger;
use Illuminate\Support\Facades\DB;




function getAC($identifier)
{
    $fy = getFiscalYear();
    return DB::table('accounts')->where('fiscal_year_id', $fy->id)->where('identifire', $identifier)->first();
}


function getTotal($acc_id)
{
    $fy = getFiscalYear();
    return DB::table('account_ledgers')->where('account_id', $acc_id)->where('fiscal_year_id', $fy->id)->where('type', 2)->sum('amount')
        - DB::table('account_ledgers')->where('account_id', $acc_id)->where('fiscal_year_id', $fy->id)->where('type', 1)->sum('amount');
}

function getVATAC()
{
    $acc = getAC('3.1');
    if ($acc == null) {
        $account = new Account();
        $account->name = 'VAT';
        $fy = getFiscalYear();
        $account->fiscal_year_id = $fy->id;
        $account->type = 3;
        $account->identifire = "3.2";
        $account->save();
    }
    return $acc;
}

function getTDSAC()
{
    $acc = getAC('3.2');
    if ($acc == null) {
        $account = new Account();
        $account->name = 'TDS';
        $account->type = 3;
        $fy = getFiscalYear();
        $account->fiscal_year_id = $fy->id;
        $account->identifire = "3.2";
        $account->save();
    }
    return $acc;
}


function pushAccountLedger($ac_id, $type, $amount, $identifier, $date, $title = '', $id = null)
{

    $fy = getFiscalYear();
    $ledger = new AccountLedger();
    $ledger->account_id = $ac_id;
    $ledger->name = $fy->name;
    $ledger->fiscal_year_id = $fy->id;
    $ledger->type = $type;
    $ledger->amount = $amount;
    $ledger->identifier = $identifier;
    $ledger->date = $date;
    $ledger->title = $title ?? '';
    $ledger->foreign_key = $id;
    $ledger->save();
    return $ledger;
}

function hasOpening($acc_id)
{
    $fy = getFiscalYear();
    return DB::table('account_ledgers')->where('account_id', $acc_id)->where('fiscal_year_id', $fy->id)->count() > 0;
}

function delACByID($ac_id, $identifier, $id)
{
    DB::where('account_id', $ac_id)->where('identifier', $identifier)->where('foreign_key', $id)->delete();
}

function delACByNOID($identifier, $id)
{
    $fy = getFiscalYear();
    DB::table('account_ledgers')->where('identifier', $identifier)->where('fiscal_year_id', $fy->id)->where('foreign_key', $id)->delete();
}

function delACByIdentifier($ac_identifier, $identifier, $id)
{
    $ac = getAC($ac_identifier);
    DB::where('account_id', $ac->id)->where('identifier', $identifier)->where('foreign_key', $id)->delete();
}

function pushTDS($amount, $identifier, $date, $title = '', $id = null)
{
    $acc = getTDSAC();
    return pushAccountLedger($acc->id, 2, $amount, $identifier, $date, $title, $id);
}

function pushCASH($type, $amount, $identifier, $date, $title = '', $id = null)
{
    $acc = getAC('1.1');
    return pushAccountLedger($acc->id, $type, $amount, $identifier, $date, $title, $id);
}

function pushBANK($acc_id, $type, $amount, $identifier, $date, $title = '', $id = null)
{
    return pushAccountLedger($acc_id, $type, $amount, $identifier, $date, $title, $id);
}

function getLedgerByNOID($identifier, $id)
{
    $fy = getFiscalYear();
    return DB::table('account_ledgers')->where('identifier', $identifier)->where('fiscal_year_id', $fy->id)->where('foreign_key', $id)->get();
}

function updateLedgerAmount($id,$amount){
    DB::update('update account_ledgers set amount=? where id=?',[$amount,$id]);
}
