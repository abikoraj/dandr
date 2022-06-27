<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreditNote;
use App\Models\CreditNoteItem;
use App\Models\FiscalYear;
use App\Models\Item;
use App\Models\PosBill;
use App\Models\PosSetting;
use App\NepaliDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PosBillingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $bills_query = PosBill::latest();
            $year = $request->year;
            $month = $request->month;
            $week = $request->week;
            $session = $request->session;
            $type = $request->type;
            $fy = FiscalYear::find($request->fy);
            $range = [];
            $data = [];
            $date = 1;
            $title = "";
            if ($type == 0) {
                $range = NepaliDate::getDate($request->year, $request->month, $request->session);
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $title = "<span class='mx-2'>Year:" . $year . "</span>";
                $title .= "<span class='mx-2'>Month:" . $month . "</span>";
                $title .= "<span class='mx-2'>Session:" . $session . "</span>";
            } elseif ($type == 1) {
                $date = $date = str_replace('-', '', $request->date1);
                $bills_query = $bills_query->where('date', '=', $date);
                $title = "<span class='mx-2'>Date:" . _nepalidate($date) . "</span>";
            } elseif ($type == 2) {
                $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $title = "<span class='mx-2'>Year:" . $year . "</span>";
                $title .= "<span class='mx-2'>Month:" . $month . "</span>";
                $title .= "<span class='mx-2'>Week:" . $week . "</span>";
            } elseif ($type == 3) {
                $range = NepaliDate::getDateMonth($request->year, $request->month);
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $title = "<span class='mx-2'>Year:" . $year . "</span>";
                $title .= "<span class='mx-2'>Month:" . $month . "</span>";
            } elseif ($type == 4) {
                $range = NepaliDate::getDateYear($request->year);
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $title = "<span class='mx-2'>Year:" . $year . "</span>";
            } elseif ($type == 5) {
                $range[1] = str_replace('-', '', $request->date1);;
                $range[2] = str_replace('-', '', $request->date2);;
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $title = "<span class='mx-2'>from:" . $request->date1 . "</span>";
                $title .= "<span class='mx-2'>To:" . $request->date2 . "</span>";
            } elseif ($type == 6) {
                $range[1] = $fy->startdate;
                $range[2] = $fy->enddate;
                $bills_query = $bills_query->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
                $title = "<span class='mx-2'>from:" . _nepalidate($range[1]) . "</span>";
                $title .= "<span class='mx-2'>To:" . _nepalidate($range[2]) . "</span>";
            }
            if ($request->customer_id != -1) {
                $bills_query = $bills_query->where('customer_id', $request->customer_id);
            }
            if ($request->filled('bill_no')) {
                $bills_query = $bills_query->where('bill_no', $request->bill_no);
            }
            $print = $request->print ?? 0;
            $return = $request->return ?? 0;
            $cancel = $request->cancel ?? 0;
            if(!$request->filled('bill_no')){
                $bills_query=$bills_query->whereRaw('(select count(*) from credit_notes where credit_notes.ref_id=pos_bills.id )=0');
            }
            if($request->filled('center_id')){

            }
            $bills = $bills_query->where('is_canceled', 0)->select('date', 'customer_name', 'id', 'bill_no', 'grandtotal')->get();
            // dd($bills);
            return view('admin.pos.list', compact('bills', 'print', 'return', 'cancel'));
        } else {
            return view('admin.pos.index');
        }
    }

    public function detail(Request $request)
    {
        if($request->out==1){
            $bill = PosBill::where('sync_id',$request->id)->first();
            $bill->billitems;
        }else{

            $bill = PosBill::find($request->id);
            $bill->billitems;
        }
        return view('admin.pos.detail', compact('bill'));
    }

    //XXX Reprint
    public function print(Request $request)
    {
        if ($request->getMethod() == "POST") {
        } else {
            return view('admin.pos.print.index');
        }
    }

    public function printInfo(Request $request)
    {
        $b = PosBill::find($request->id);
        $b->billitems;
        $b->payment;
        $b->user = Auth::user();

        return response()->json($b);
    }

    public function creditNoteInfo(Request $request)
    {
        $note= CreditNote::find($request->id);
        $note->noteItems;
        $note->user = Auth::user();
        return response()->json($note);
    }

    //XXX Sales Return
    public function salesReturn(Request $request)
    {
        if ($request->getMethod() == "POST") {
            $b = PosBill::find($request->id);
            $b->billitems;
            // $b->payment;
            // $b->user=Auth::user();
            return view('admin.pos.return.init', ['bill' => $b]);
        } else {
            return view('admin.pos.return.index');
        }
    }

    public function salesReturnSingle(PosBill $bill)
    {
        $note=CreditNote::where('ref_id',$bill->id)->first();
        $hasNote=$note!=null;
        if($hasNote){
            $note->noteItems;
            return view('admin.pos.return.single', compact('note','hasNote'));

        }else{

            $bill->billitems;
            return view('admin.pos.return.single', compact('bill','hasNote'));
        }
    }

    public function initSalesReturn(Request $request)
    {
        $setting = PosSetting::first();
        if ($setting == null) {
            return response("Day Not Opened, Please Contact Administrator.", 500);
        } else {
            if ($setting->open != 1) {
                return response("Day Not Opened, Please Contact Administrator.", 500);
            }
        }

        $note=null;
        if(env('cancelonreturn',false)){
           $note= $this->retrunWithCancel($request,$setting->fiscalYear(),$setting->date);

        }else{
           $note=$this->generateCreditNote($request,$setting->fiscalYear(),$setting->date);
        }
        return view('admin.pos.return.creditnote',compact('note'));
    }

    private function retrunWithCancel(Request $request,$fy,$date){

    }



    private function generateCreditNote(Request $request,$fy,$date){
        $amount=0;
        $discount=0;
        $taxable=0;
        $tax=0;
        $total=0;
        $bill = PosBill::find($request->id);
        $note = new CreditNote();
        $note->date = $date;
        $note->ref_id = $bill->id;
        $note->fiscal_year_id = $fy->id;
        $note->bill_no = $bill->bill_no;
        $note->customer_name = $request->customer_name;
        $note->customer_address = $request->customer_address;
        $note->customer_phone = $request->customer_phone;
        $note->customer_pan = $request->customer_pan;
        $note->remarks = $request->remarks;
        $note->total=$amount;
        $note->discount=$discount;
        $note->taxable=$taxable;
        $note->tax=$tax;
        $note->grandtotal=$total;
        $note->paid=0;
        $note->save();
        $noteitems=[];
        foreach ($bill->billitems as $key => $billitem) {
            if ($request->filled('bill_item_' . $billitem->id)) {
                if ($request->input('bill_item_' . $billitem->id) > 0) {
                    $rate=$billitem->rate;
                    $dis_per=$billitem->discount/$billitem->qty;
                    $qty=$request->input('bill_item_' . $billitem->id);
                    $noteitem=new CreditNoteItem();
                    $noteitem->credit_note_id=$note->id;
                    $noteitem->amount=$rate*$qty;
                    $noteitem->rate=$rate;
                    $noteitem->qty=$qty;
                    $noteitem->discount=$dis_per*$qty;

                    $tot=$noteitem->amount-$noteitem->discount;

                    if($billitem->use_tax==1){
                        $noteitem->taxable=$noteitem->amount-$noteitem->discount;
                        $noteitem->tax=truncate_decimals((($noteitem->taxable * $billitem->tax_per)/100),2);
                    }else{
                        $noteitem->tax=0;
                        $noteitem->taxable=0;
                    }
                    $noteitem->total=$tot+$noteitem->tax;
                    $noteitem->item_id=$billitem->item_id;
                    $noteitem->name=$billitem->name;
                    $noteitem->save();

                    $item=Item::where('id',$noteitem->item_id)->select('id','stock','trackstock')->first();
                    if($item->trackstock==1){
                        $item->stock+=$noteitem->qty;
                        $item->save();
                    }

                    $amount+=$noteitem->amount;
                    $discount+=$noteitem->discount;
                    $taxable+=$noteitem->taxable;
                    $tax+=$noteitem->tax;
                    $total+=$noteitem->total;

                    array_push($noteitems,$noteitem);
                }
            }
        }

        $note->total=$amount;
        $note->discount=$discount;
        $note->taxable=$taxable;
        $note->tax=$tax;
        $note->grandtotal=$total;
        $note->user_id=Auth::user()->id;
        $note->save();
        $note->noteItems=$noteitems;
        return $note;
    }

    public function printSalesReturn(CreditNote $note,Request $request){
        $note->noteItems;
        return view('admin.print.creditnote',compact('note'));
    }
}
