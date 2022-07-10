<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManufactureController extends Controller
{
    public function list(Request $request)
    {
        $processes = DB::table('manufacture_processes')
            ->join('manufactured_products', 'manufactured_products.id', '=', 'manufacture_processes.manufactured_product_id')
            ->join('items', 'items.id', '=', 'manufactured_products.item_id');
        if (env('multi_package', false)) {
            $processes = $processes->join('conversions', 'conversions.id', '=', 'items.conversion_id');
        }
        $processes = $processes->select(
            DB::raw(
                'items.title,' .
                    (env('multi_package', false) ? 'conversions.name as unit,' : 'items.unit,')
                    . 'manufacture_processes.id,
                manufacture_processes.expected,
                manufacture_processes.start,
                manufacture_processes.stage,
                manufacture_processes.expected_end'
            )
        )
        ->get()->groupBy('stage');
        return response()->json($processes);
    }
}
