<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    //
    public function index(Request $request)
    {
        $items=DB::select('select id,title,sell_price,wholesale,stock ,number as barcode,unit,taxable,tax,
        (select IFNULL(sum(amount),0) from center_stocks where item_id=items.id and center_id=?) as center_stock,
        (select IFNULL(sum(rate),0) from center_stocks where item_id=items.id and center_id=?) as center_rate,
        (select IFNULL(sum(wholesale),0) from center_stocks where item_id=items.id and center_id=?) as center_wholesale
        from items ',[$request->center_id,$request->center_id,$request->center_id]);

        return response(json_encode($items,JSON_PRESERVE_ZERO_FRACTION));
    }
}
