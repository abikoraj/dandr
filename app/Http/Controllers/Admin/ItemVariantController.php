<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemVariant;
use App\Models\ItemVariantPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemVariantController extends Controller
{
    public function index(Item $item, Request $request)
    {
        if ($request->getMethod() == "POST") {
        } else {
            $centers = [];
            if (env('multi_stock', false)) {
                $centers = DB::table('centers')->get(['id', 'name']);
            }
            $variants = DB::table('item_variants')->where('item_id', $item->id)->get();
            if ($variants->count() > 0) {
                $variant_prices = DB::table('item_variant_prices')->whereIn('item_variant_id', $variants->pluck('id'))->get();
            } else {
                $variant_prices = [];
            }
            return view('admin.item.variants.index', compact('item', 'variants', 'variant_prices', 'centers'));
        }
    }

    public function update($id, Request $request)
    {
        $variant = ItemVariant::where('id', $id)->first();
        if (!env('multi_package', false)) {
            $variant->unit = $request->unit;
        }

        if (!env('multi_stock', false)) {
            $variant->wholesale = $request->wholesale ?? 0;
            $variant->price = $request->price ?? 0;
        }
        if (env('multi_package', false)) {

            $item = DB::table('items')->where('id', $variant->item_id)->first(['conversion_id']);
            // dd($item);/
            $main = DB::table('conversions')->where('id', $item->conversion_id)->first();
            $local = DB::table('conversions')->where('id', $variant->conversion_id)->first();
            if($main->parent_id==0){
                $ratio = $local->local / $local->main;
                $ratio=1/$ratio;
            }else{

                if ($local->parent_id == 0) {
                    $ratio = $main->local / $main->main;
                } else {
                    $ratio1 = $main->local / $main->main;
                    $ratio2 = $local->local / $local->main;
                    $ratio = $ratio1 / $ratio2;
                }
            }
            $variant->ratio = $ratio;
        } else {
            $variant->ratio = $request->ratio;
        }
        $variant->save();

        if (env('multi_stock', false)) {
            if ($request->filled('centers')) {
                foreach ($request->centers as $key => $center_id) {
                    $price = $request->input('price_' . $center_id);
                    $wholesale = $request->input('wholesale_' . $center_id);

                    $variantPrice = ItemVariantPrice::where('center_id', $center_id)->where('item_variant_id', $variant->id)->first();
                    if ($variantPrice == null) {
                        $variantPrice = new ItemVariantPrice();
                        $variantPrice->center_id = $center_id;
                        $variantPrice->item_variant_id = $variant->id;
                    }
                    $variantPrice->wholesale = $wholesale;
                    $variantPrice->price = $price;
                    $variantPrice->save();
                    if ($center_id == env('maincenter', -1)) {
                        $variant->price = $price;
                        $variant->wholesale = $wholesale;
                        $variant->save();
                    }
                }
            }
        }

        return redirect()->back()->with('message', 'Varaint Updated sucessfully');
    }


    public function del($id)
    {
        DB::delete('delete from item_variant_prices where item_variant_id = ?', [$id]);
        DB::delete('delete from item_variants where id = ?', [$id]);
        return redirect()->back()->with('message', 'Varaint deleted sucessfully');
    }
    public function add($id, Request $request)
    {
        if ($request->getMethod() == "POST") {
            $variant = new ItemVariant();
            $variant->item_id = $id;
            $variant->unit = $request->unit ?? '';
            $variant->wholesale = $request->wholesale ?? 0;
            $variant->price = $request->price ?? 0;
            if ($request->filled('conversion_id')) {
                $variant->conversion_id = $request->conversion_id;
                $variant->unit = DB::table('conversions')->where('id', $request->conversion_id)->first(['name'])->name;
            }

            if (env('multi_package', false)) {

                $item = DB::table('items')->where('id', $id)->first(['conversion_id']);
                $main = DB::table('conversions')->where('id', $item->conversion_id)->first();
                $local = DB::table('conversions')->where('id', $variant->conversion_id)->first();
                if($main->parent_id==0){
                    $ratio = $local->local / $local->main;
                    $ratio=1/$ratio;
                }else{

                    if ($local->parent_id == 0) {
                        $ratio = $main->local / $main->main;
                    } else {
                        $ratio1 = $main->local / $main->main;
                        $ratio2 = $local->local / $local->main;
                        $ratio = $ratio1 / $ratio2;
                    }
                }
                $variant->ratio = $ratio;
            } else {
                $variant->ratio = $request->ratio;
            }

            $variant->save();
            if (env('multi_stock', false)) {
                if ($request->filled('centers')) {
                    foreach ($request->centers as $key => $center_id) {
                        $price = $request->input('price_' . $center_id);
                        $wholesale = $request->input('wholesale_' . $center_id);
                        $variantPrice = new ItemVariantPrice();
                        $variantPrice->center_id = $center_id;
                        $variantPrice->wholesale = $wholesale;
                        $variantPrice->price = $price;
                        $variantPrice->item_variant_id = $variant->id;
                        $variantPrice->save();
                        if ($center_id == env('maincenter', -1)) {
                            $variant->price = $price;
                            $variant->wholesale = $wholesale;
                            $variant->save();
                        }
                    }
                }
            }
            return response('ok');
        } else {


            $centers = [];
            $units = [];
            if (env('multi_stock', false)) {
                $centers = DB::table('centers')->get(['id', 'name']);
            }
            if (env('multi_package', false)) {
                $item = DB::table('items')->where('id', $id)->first(['id', 'conversion_id']);
                $ids = DB::table('item_variants')->where('item_id', $id)->pluck('conversion_id');
                $main = DB::table('conversions')->where('id', $item->conversion_id)->first(['id', 'name', 'parent_id']);
                if ($main->parent_id != 0) {
                    $units = DB::table('conversions')->where(function ($query) use ($main) {
                        $query->where('id', $main->parent_id);
                        $query->orWhere('parent_id', $main->parent_id);
                    })
                        ->where('id', '<>', $item->conversion_id);
                } else {
                    $units = DB::table('conversions')->Where('parent_id', $main->id);
                }
                if ($ids->count() > 0) {
                    $units = $units->whereNotIn('id', $ids);
                }
                $units = $units->orderBy('id', 'asc')->get(['id', 'name']);
                if (count($units) == 0) {
                    return response('No Units remaning in group For Variant');
                }
            } else {
            }
            return view('admin.item.variants.add', compact('units', 'item', 'centers'));
        }
    }
}
