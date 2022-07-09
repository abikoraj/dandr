<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expcategory;
use App\Models\Expense;
use App\NepaliDate;
use App\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{

    //XXX expense categories

    public function categoryIndex()
    {
        return view('admin.expense.category.index');
    }

    public function categoryAdd(Request $request)
    {
        $expcat = new Expcategory();
        $expcat->name = $request->name;
        $expcat->save();
        return redirect()->back()->with('message', 'Category added successfully!');
    }

    public function categoryUpdate(Request $request)
    {
        // dd($request->all());
        $expcat = Expcategory::where('id', $request->id)->first();
        $expcat->name = $request->name;
        $expcat->save();
        return redirect()->back()->with('message', 'Category updated successfully!');
    }

    public function categoryExpenses(Request $request)
    {
        $exps = Expense::latest()->where('expcategory_id', $request->id)->get();
        return view('admin.expense.list', compact('exps'));
    }


    //XXX expenses
    public function index()
    {
        return view('admin.expense.index');
    }

    public function add(Request $request)
    {
        $date = str_replace('-', '', $request->date);
        $exp = new Expense();
        $exp->title = $request->title;
        $exp->amount = $request->amount;
        $exp->date = $date;
        $exp->payment_detail = $request->payment_detail;
        $exp->payment_by = $request->payment_by;
        $exp->remark = $request->remark;
        $exp->expcategory_id = $request->cat_id;
        $exp->user_id = Auth::user()->id;
        $exp->save();
        new PaymentManager($request,$exp->id,201);
        return view('admin.expense.single', compact('exp'));
    }
    public function edit(Request $request)
    {
        $exp=Expense::where('id', $request->id)->first();
        $paymentData=PaymentManager::loadUpdateID($exp->id,201);
        return view('admin.expense.edit', compact('exp','paymentData'));
    }

    public function update(Request $request)
    {
        $date = str_replace('-', '', $request->date);
        $exp = Expense::where('id', $request->id)->first();
        $exp->title = $request->title;
        $exp->amount = $request->amount;
        $exp->date = $date;
        $exp->payment_detail = $request->payment_detail;
        $exp->payment_by = $request->payment_by;
        $exp->remark = $request->remark;
        $exp->expcategory_id = $request->cat_id;
        $exp->user_id = Auth::user()->id;
        $exp->save();
        PaymentManager::update($exp->id,201,$request);
        return view('admin.expense.single', compact('exp'));
    }

    public function list(Request $request)
    {

        $exps = Expense::latest()->get();
        return view('admin.expense.list', compact('exps'));
    }

    public function delete(Request $request)
    {
        $exp = Expense::find($request->id);
        PaymentManager::remove($request->id,201);
        $exp->delete();
    }

    public function load(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $week = $request->week;
        $session = $request->session;
        $type = $request->type;
        $range = [];
        $data = [];
        $date = 1;
        $title = "";
        $expense = Expense::where('id', '>', 0);
        if ($type == 0) {
            $range = NepaliDate::getDate($request->year, $request->month, $request->session);
            $expense = $expense->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>Year: " . $year . "</span>";
            $title .= "<span class='mx-2'>Month: " . $month . "</span>";
            $title .= "<span class='mx-2'>Session: " . $session . "</span>";
        } elseif ($type == 1) {
            $date = $date = str_replace('-', '', $request->date1);
            $expense = $expense->where('date', '=', $date);
            $title = "<span class='mx-2'>Date: " . _nepalidate($date) . "</span>";
        } elseif ($type == 2) {
            $range = NepaliDate::getDateWeek($request->year, $request->month, $request->week);
            $expense = $expense->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>Year: " . $year . "</span>";
            $title .= "<span class='mx-2'>Month: " . $month . "</span>";
            $title .= "<span class='mx-2'>Week: " . $week . "</span>";
        } elseif ($type == 3) {
            $range = NepaliDate::getDateMonth($request->year, $request->month);
            $expense = $expense->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>Year: " . $year . "</span>";
            $title .= "<span class='mx-2'>Month: " . $month . "</span>";
        } elseif ($type == 4) {
            $range = NepaliDate::getDateYear($request->year);
            $expense = $expense->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>Year: " . $year . "</span>";
        } elseif ($type == 5) {
            $range[1] = str_replace('-', '', $request->date1);;
            $range[2] = str_replace('-', '', $request->date2);;
            $expense = $expense->where('date', '>=', $range[1])->where('date', '<=', $range[2]);
            $title = "<span class='mx-2'>from: " . $request->date1 . "</span>";
            $title .= "<span class='mx-2'>To: " . $request->date2 . "</span>";
        }
        if ($request->cat_id != -1) {
            $expense = $expense->where('expcategory_id', $request->cat_id);
        }
        // dd($ledger->toSql(),$ledger->getBindings());
        $exps = $expense->orderBy('id', 'asc')->get();
        return view('admin.expense.list', compact('exps','title'));
    }
}
