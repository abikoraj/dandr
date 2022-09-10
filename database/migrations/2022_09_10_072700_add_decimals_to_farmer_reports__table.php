<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDecimalsToFarmerReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('farmer_reports', function (Blueprint $table) {
        //     $table->decimal('milk',18,2)->default(0)->change();
        //     $table->decimal('snf',18,2)->default(0)->change();
        //     $table->decimal('fat',18,2)->default(0)->change();
        //     $table->decimal('total',18,2)->default(0)->change();
        //     $table->decimal('due',18,2)->default(0)->change();
        //     $table->decimal('prevdue',18,2)->default(0)->change();
        //     $table->decimal('advance',18,2)->default(0)->change();
        //     $table->decimal('netttotal',18,2)->default(0)->change();
        //     $table->decimal('balance',18,2)->default(0)->change();
        //     $table->decimal('grandtotal',18,2)->default(0)->change();
        //     $table->decimal('prevbalance',18,2)->default(0)->change();
        //     $table->decimal('paidamount',18,2)->default(0)->change();
        //     $table->decimal('fpaid',18,2)->default(0)->change();
        //     $table->decimal('protsahan_amount',18,2)->default(0)->change();
        //     $table->decimal('transport_amount',18,2)->default(0)->change();
        // });
        // Schema::table('sell_items', function (Blueprint $table) {
        //     $table->decimal('total',18,2)->default(0)->change();
        //     $table->decimal('qty',18,2)->default(0)->change();
        //     $table->decimal('paid',18,2)->default(0)->change();
        //     $table->decimal('due',18,2)->default(0)->change();
        //     $table->decimal('due',18,2)->default(0)->change();
        // });

        // Schema::table('farmerpayments', function (Blueprint $table) {
        //     $table->decimal('amount',18,2)->default(0)->change();

        // });
        // Schema::table('distributor_payments', function (Blueprint $table) {
        //     $table->decimal('amount',18,2)->default(0)->change();
        // });

        // Schema::table('bills', function (Blueprint $table) {
        //     $table->decimal('net_total',18,2)->default(0)->change();

        // });

        // Schema::table('pos_bill_items', function (Blueprint $table) {
        //     $table->decimal('amount',18,2)->default(0)->change();

        // });

        // Schema::table('advances', function (Blueprint $table) {
        //     $table->decimal('amount',18,2)->default(0)->change();

        // });

        Schema::table('accounts', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('account_ledgers', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('advances', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('banks', function (Blueprint $table) {
            $table->decimal('balance', 18, 2)->default(0)->change();
        });
        Schema::table('bills', function (Blueprint $table) {
            $table->decimal('grandtotal', 18, 2)->default(0)->change();
            $table->decimal('paid', 18, 2)->default(0)->change();
            $table->decimal('due', 18, 2)->default(0)->change();
            $table->decimal('return', 18, 2)->default(0)->change();
            $table->decimal('dis', 18, 2)->default(0)->change();
            $table->decimal('net_total', 18, 2)->default(0)->change();
        });
        Schema::table('bill_expenses', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('bill_items', function (Blueprint $table) {
            $table->decimal('rate', 18, 2)->default(0)->change();
            $table->decimal('qty', 18, 2)->default(0)->change();
            $table->decimal('amount', 18, 2)->default(0)->change();
            $table->decimal('total', 18, 2)->default(0)->change();
            $table->decimal('reward', 18, 2)->default(0)->change();
            $table->decimal('points', 18, 2)->default(0)->change();
        });
        Schema::table('centers', function (Blueprint $table) {
            $table->decimal('fat_rate', 18, 2)->default(0)->change();
            $table->decimal('snf_rate', 18, 2)->default(0)->change();
            $table->decimal('bonus', 18, 2)->default(0)->change();
            $table->decimal('tc', 18, 2)->default(0)->change();
            $table->decimal('cc', 18, 2)->default(0)->change();
            $table->decimal('protsahan', 18, 2)->default(0)->change();
        });
        Schema::table('center_stocks', function (Blueprint $table) {
            $table->decimal('amount', 18, 5)->default(0)->change();
            $table->decimal('rate', 18, 2)->default(0)->change();
            $table->decimal('wholesale', 18, 2)->default(0)->change();
        });
        Schema::table('conversions', function (Blueprint $table) {
            $table->decimal('main', 18, 4)->default(0)->change();
            $table->decimal('local', 18, 4)->default(0)->change();
        });
        Schema::table('counter_statuses', function (Blueprint $table) {
            $table->decimal('request', 18, 2)->default(0)->change();
            $table->decimal('opening', 18, 2)->default(0)->change();
            $table->decimal('current', 18, 2)->default(0)->change();
            $table->decimal('closing', 18, 2)->default(0)->change();
        });
        Schema::table('credit_notes', function (Blueprint $table) {
            $table->decimal('total', 18, 2)->default(0)->change();
            $table->decimal('discount', 18, 2)->default(0)->change();
            $table->decimal('taxable', 18, 2)->default(0)->change();
            $table->decimal('tax', 18, 2)->default(0)->change();
            $table->decimal('grandtotal', 18, 2)->default(0)->change();
            $table->decimal('rounding', 18, 2)->default(0)->change();
            $table->decimal('paid', 18, 2)->default(0)->change();
            $table->decimal('due', 18, 2)->default(0)->change();
            $table->decimal('return', 18, 2)->default(0)->change();
        });
        Schema::table('credit_note_items', function (Blueprint $table) {
            $table->decimal('rate', 18, 2)->default(0)->change();
            $table->decimal('discount', 18, 2)->default(0)->change();
            $table->decimal('taxable', 18, 2)->default(0)->change();
            $table->decimal('tax', 18, 2)->default(0)->change();
            $table->decimal('amount', 18, 2)->default(0)->change();
            $table->decimal('total', 18, 2)->default(0)->change();
            $table->decimal('qty', 18, 2)->default(0)->change();
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->decimal('points', 18, 2)->default(0)->change();
        });
        Schema::table('customer_payments', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('distributerreqs', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('distributers', function (Blueprint $table) {
            $table->decimal('rate', 18, 2)->default(0)->change();
            $table->decimal('amount', 18, 2)->default(0)->change();
            $table->decimal('snf_rate', 18, 4)->default(0)->change();
            $table->decimal('fat_rate', 18, 4)->default(0)->change();
            $table->decimal('added_rate', 18, 4)->default(0)->change();
            $table->decimal('fixed_rate', 18, 4)->default(0)->change();
        });
        Schema::table('distributersnffats', function (Blueprint $table) {
            $table->decimal('snf', 18, 2)->default(0)->change();
            $table->decimal('fat', 18, 2)->default(0)->change();
        });
        Schema::table('distributer_milks', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('distributer_milk_reports', function (Blueprint $table) {
            $table->decimal('milk', 18, 2)->default(0)->change();
            $table->decimal('snf', 18, 2)->default(0)->change();
            $table->decimal('fat', 18, 2)->default(0)->change();
            $table->decimal('rate', 18, 2)->default(0)->change();
            $table->decimal('is_fixed', 18, 2)->default(0)->change();
            $table->decimal('total', 18, 2)->default(0)->change();
        });
        Schema::table('distributorsells', function (Blueprint $table) {
            $table->decimal('rate', 18, 2)->default(0)->change();
            $table->decimal('qty', 18, 2)->default(0)->change();
            $table->decimal('total', 18, 2)->default(0)->change();
            $table->decimal('paid', 18, 2)->default(0)->change();
            $table->decimal('deu', 18, 2)->default(0)->change();
        });
        Schema::table('distributor_payments', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('salary', 18, 2)->default(0)->change();
        });
        Schema::table('employee_advances', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('employee_reports', function (Blueprint $table) {
            $table->decimal('prevbalance', 18, 2)->default(0)->change();
            $table->decimal('advance', 18, 2)->default(0)->change();
            $table->decimal('salary', 18, 2)->default(0)->change();
            $table->decimal('balance', 18, 2)->default(0)->change();
        });
        Schema::table('expenses', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('extra_incomes', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('farmerpayments', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('farmers', function (Blueprint $table) {
            $table->decimal('rate', 18, 2)->default(0)->change();
            $table->decimal('ts_amount', 18, 2)->default(0)->change();
            $table->decimal('protsahan', 18, 2)->default(0)->change();
            $table->decimal('transport', 18, 2)->default(0)->change();
        });
        Schema::table('farmer_reports', function (Blueprint $table) {
            $table->decimal('milk', 18, 2)->default(0)->change();
            $table->decimal('snf', 18, 2)->default(0)->change();
            $table->decimal('fat', 18, 2)->default(0)->change();
            $table->decimal('rate', 18, 2)->default(0)->change();
            $table->decimal('total', 18, 2)->default(0)->change();
            $table->decimal('due', 18, 2)->default(0)->change();
            $table->decimal('prevdue', 18, 2)->default(0)->change();
            $table->decimal('advance', 18, 2)->default(0)->change();
            $table->decimal('nettotal', 18, 2)->default(0)->change();
            $table->decimal('balance', 18, 2)->default(0)->change();
            $table->decimal('bonus', 18, 2)->default(0)->change();
            $table->decimal('cc', 18, 2)->default(0)->change();
            $table->decimal('tc', 18, 2)->default(0)->change();
            $table->decimal('grandtotal', 18, 2)->default(0)->change();
            $table->decimal('prevbalance', 18, 2)->default(0)->change();
            $table->decimal('paidamount', 18, 2)->default(0)->change();
            $table->decimal('fpaid', 18, 2)->default(0)->change();
            $table->decimal('protsahan_amount', 18, 2)->default(0)->change();
            $table->decimal('transport_amount', 18, 2)->default(0)->change();
        });
        Schema::table('fixed_assets', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
            $table->decimal('depreciation', 18, 2)->default(0)->change();
            $table->decimal('full_amount', 18, 2)->default(0)->change();
            $table->decimal('salvage_amount', 18, 2)->default(0)->change();
            $table->decimal('appreciation', 18, 2)->default(0)->change();
        });
        Schema::table('fixed_asset_categories', function (Blueprint $table) {
            $table->decimal('depreciation', 18, 2)->default(0)->change();
        });
        Schema::table('free_items', function (Blueprint $table) {
            $table->decimal('qty', 18, 2)->default(0)->change();
        });
        Schema::table('items', function (Blueprint $table) {
            $table->decimal('cost_price', 18, 2)->default(0)->change();
            $table->decimal('sell_price', 18, 2)->default(0)->change();
            $table->decimal('stock', 18, 5)->default(0)->change();
            $table->decimal('reward_percentage', 18, 2)->default(0)->change();
            $table->decimal('dis_price', 18, 2)->default(0)->change();
            $table->decimal('tax', 18, 2)->default(0)->change();
            $table->decimal('wholesale', 18, 2)->default(0)->change();
            $table->decimal('points', 18, 2)->default(0)->change();
        });
        Schema::table('item_variants', function (Blueprint $table) {
            $table->decimal('wholesale', 18, 2)->default(0)->change();
            $table->decimal('price', 18, 2)->default(0)->change();
            $table->decimal('ratio', 18, 5)->default(0)->change();
        });
        Schema::table('item_variant_prices', function (Blueprint $table) {
            $table->decimal('wholesale', 18, 2)->default(0)->change();
            $table->decimal('price', 18, 2)->default(0)->change();
        });
        Schema::table('ledgers', function (Blueprint $table) {
            $table->decimal('cr', 18, 2)->default(0)->change();
            $table->decimal('dr', 18, 2)->default(0)->change();
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('manufactured_products', function (Blueprint $table) {
            $table->decimal('amount', 18, 3)->default(0)->change();
        });
        Schema::table('manufactured_product_items', function (Blueprint $table) {
            $table->decimal('amount', 18, 3)->default(0)->change();
        });
        Schema::table('manufactureitems', function (Blueprint $table) {
            $table->decimal('req_qty', 18, 2)->default(0)->change();
        });
        Schema::table('manufactures', function (Blueprint $table) {
            $table->decimal('qty', 18, 2)->default(0)->change();
        });
        Schema::table('manufacture_processes', function (Blueprint $table) {
            $table->decimal('expected', 18, 3)->default(0)->change();
            $table->decimal('actual', 18, 3)->default(0)->change();
        });
        Schema::table('manufacture_process_items', function (Blueprint $table) {
            $table->decimal('amount', 18, 3)->default(0)->change();
        });
        Schema::table('manufacture_unused_items', function (Blueprint $table) {
            $table->decimal('amount', 18, 3)->default(0)->change();
        });
        Schema::table('manufacture_wastages', function (Blueprint $table) {
            $table->decimal('amount', 18, 3)->default(0)->change();
            $table->decimal('rate', 18, 2)->default(0)->change();
        });
        Schema::table('milkdatas', function (Blueprint $table) {
            $table->decimal('m_amount', 18, 2)->default(0)->change();
            $table->decimal('e_amount', 18, 2)->default(0)->change();
        });
        Schema::table('milk_payments', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('offer_items', function (Blueprint $table) {
            $table->decimal('min', 18, 3)->default(0)->change();
            $table->decimal('max', 18, 3)->default(0)->change();
            $table->decimal('buy', 18, 3)->default(0)->change();
            $table->decimal('flat', 18, 3)->default(0)->change();
            $table->decimal('percentage', 18, 3)->default(0)->change();
            $table->decimal('get', 18, 3)->default(0)->change();
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('qty', 18, 2)->default(0)->change();
            $table->decimal('rate', 18, 2)->default(0)->change();
        });
        Schema::table('pos_bills', function (Blueprint $table) {
            $table->decimal('total', 18, 2)->default(0)->change();
            $table->decimal('discount', 18, 2)->default(0)->change();
            $table->decimal('taxable', 18, 2)->default(0)->change();
            $table->decimal('tax', 18, 2)->default(0)->change();
            $table->decimal('grandtotal', 18, 2)->default(0)->change();
            $table->decimal('rounding', 18, 2)->default(0)->change();
            $table->decimal('paid', 18, 2)->default(0)->change();
            $table->decimal('due', 18, 2)->default(0)->change();
            $table->decimal('return', 18, 2)->default(0)->change();
            $table->decimal('points', 18, 2)->default(0)->change();
            $table->decimal('ldiscount', 18, 2)->default(0)->change();
        });
        Schema::table('pos_bill_items', function (Blueprint $table) {
            $table->decimal('rate', 18, 2)->default(0)->change();
            $table->decimal('qty', 18, 5)->default(0)->change();
            $table->decimal('amount', 18, 2)->default(0)->change();
            $table->decimal('total', 18, 2)->default(0)->change();
            $table->decimal('discount', 18, 2)->default(0)->change();
            $table->decimal('taxable', 18, 2)->default(0)->change();
            $table->decimal('tax', 18, 2)->default(0)->change();
            $table->decimal('tax_per', 18, 2)->default(0)->change();
            $table->decimal('conversion_qty', 18, 3)->default(0)->change();
            $table->decimal('conversion_rate', 18, 2)->default(0)->change();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 18, 2)->default(0)->change();
            $table->decimal('minqty', 18, 2)->default(0)->change();
            $table->decimal('discount', 18, 2)->default(0)->change();
        });
        Schema::table('product_purchases', function (Blueprint $table) {
            $table->decimal('total', 18, 2)->default(0)->change();
        });
        Schema::table('product_purchase_items', function (Blueprint $table) {
            $table->decimal('rate', 18, 2)->default(0)->change();
            $table->decimal('qty', 18, 2)->default(0)->change();
        });
        Schema::table('repackage_items', function (Blueprint $table) {
            $table->decimal('from_amount', 18, 3)->default(0)->change();
            $table->decimal('to_amount', 18, 3)->default(0)->change();
        });
        Schema::table('repackaging_costs', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('repackaging_materials', function (Blueprint $table) {
            $table->decimal('rate', 18, 2)->default(0)->change();
            $table->decimal('qty', 18, 5)->default(0)->change();
        });
        Schema::table('salary_payments', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('sellitems', function (Blueprint $table) {
            $table->decimal('total', 18, 2)->default(0)->change();
            $table->decimal('qty', 18, 2)->default(0)->change();
            $table->decimal('rate', 18, 2)->default(0)->change();
            $table->decimal('paid', 18, 2)->default(0)->change();
            $table->decimal('due', 18, 2)->default(0)->change();
        });
        Schema::table('simple_manufacturing_items', function (Blueprint $table) {
            $table->decimal('amount', 18, 5)->default(0)->change();
        });
        Schema::table('snffats', function (Blueprint $table) {
            $table->decimal('snf', 18, 2)->default(0)->change();
            $table->decimal('fat', 18, 2)->default(0)->change();
        });
        Schema::table('stocks', function (Blueprint $table) {
            $table->decimal('opening', 18, 2)->default(0)->change();
            $table->decimal('closing', 18, 2)->default(0)->change();
        });
        Schema::table('stock_out_items', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('supplierbillitems', function (Blueprint $table) {
            $table->decimal('rate', 18, 2)->default(0)->change();
            $table->decimal('qty', 18, 3)->default(0)->change();
            $table->decimal('remaning', 18, 3)->default(0)->change();
            $table->decimal('conversion_qty', 18, 3)->default(0)->change();
            $table->decimal('conversion_rate', 18, 2)->default(0)->change();
        });
        Schema::table('supplierbills', function (Blueprint $table) {
            $table->decimal('total', 18, 2)->default(0)->change();
            $table->decimal('paid', 18, 2)->default(0)->change();
            $table->decimal('due', 18, 2)->default(0)->change();
            $table->decimal('transport_charge', 18, 2)->default(0)->change();
            $table->decimal('taxable', 18, 2)->default(0)->change();
            $table->decimal('tax', 18, 2)->default(0)->change();
            $table->decimal('discount', 18, 2)->default(0)->change();
        });
        Schema::table('supplierpayments', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('amount', 18, 2)->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('farmer_reports_', function (Blueprint $table) {
            Schema::table('accounts', function (Blueprint $table) {
                $table->decimal('amount', 18, 2)->default(0)->change();
            });
            Schema::table('account_ledgers', function (Blueprint $table) {
                $table->decimal('amount', 18, 2)->default(0)->change();
            });
            Schema::table('advances', function (Blueprint $table) {
                $table->decimal('amount', 8, 2)->default(0)->change();
            });
            Schema::table('banks', function (Blueprint $table) {
                $table->decimal('balance', 18, 2)->default(0)->change();
            });
            Schema::table('bills', function (Blueprint $table) {
                $table->decimal('grandtotal', 12, 2)->default(0)->change();
                $table->decimal('paid', 12, 2)->default(0)->change();
                $table->decimal('due', 12, 2)->default(0)->change();
                $table->decimal('return', 12, 2)->default(0)->change();
                $table->decimal('dis', 12, 2)->default(0)->change();
                $table->decimal('net_total', 8, 2)->default(0)->change();
            });
            Schema::table('bill_expenses', function (Blueprint $table) {
                $table->decimal('amount', 18, 2)->default(0)->change();
            });
            Schema::table('bill_items', function (Blueprint $table) {
                $table->decimal('rate', 12, 2)->default(0)->change();
                $table->decimal('qty', 12, 2)->default(0)->change();
                $table->decimal('amount', 12, 2)->default(0)->change();
                $table->decimal('total', 12, 2)->default(0)->change();
                $table->decimal('reward', 8, 2)->default(0)->change();
                $table->decimal('points', 8, 2)->default(0)->change();
            });
            Schema::table('centers', function (Blueprint $table) {
                $table->decimal('fat_rate', 8, 2)->default(0)->change();
                $table->decimal('snf_rate', 8, 2)->default(0)->change();
                $table->decimal('bonus', 18, 2)->default(0)->change();
                $table->decimal('tc', 18, 2)->default(0)->change();
                $table->decimal('cc', 18, 2)->default(0)->change();
                $table->decimal('protsahan', 8, 2)->default(0)->change();
            });
            Schema::table('center_stocks', function (Blueprint $table) {
                $table->decimal('amount', 18, 5)->default(0)->change();
                $table->decimal('rate', 18, 2)->default(0)->change();
                $table->decimal('wholesale', 8, 2)->default(0)->change();
            });
            Schema::table('conversions', function (Blueprint $table) {
                $table->decimal('main', 18, 4)->default(0)->change();
                $table->decimal('local', 18, 4)->default(0)->change();
            });
            Schema::table('counter_statuses', function (Blueprint $table) {
                $table->decimal('request', 18, 2)->default(0)->change();
                $table->decimal('opening', 18, 2)->default(0)->change();
                $table->decimal('current', 18, 2)->default(0)->change();
                $table->decimal('closing', 18, 2)->default(0)->change();
            });
            Schema::table('credit_notes', function (Blueprint $table) {
                $table->decimal('total', 18, 2)->default(0)->change();
                $table->decimal('discount', 18, 2)->default(0)->change();
                $table->decimal('taxable', 18, 2)->default(0)->change();
                $table->decimal('tax', 18, 2)->default(0)->change();
                $table->decimal('grandtotal', 18, 2)->default(0)->change();
                $table->decimal('rounding', 18, 2)->default(0)->change();
                $table->decimal('paid', 18, 2)->default(0)->change();
                $table->decimal('due', 18, 2)->default(0)->change();
                $table->decimal('return', 18, 2)->default(0)->change();
            });
            Schema::table('credit_note_items', function (Blueprint $table) {
                $table->decimal('rate', 12, 2)->default(0)->change();
                $table->decimal('discount', 12, 2)->default(0)->change();
                $table->decimal('taxable', 12, 2)->default(0)->change();
                $table->decimal('tax', 12, 2)->default(0)->change();
                $table->decimal('amount', 12, 2)->default(0)->change();
                $table->decimal('total', 12, 2)->default(0)->change();
                $table->decimal('qty', 12, 2)->default(0)->change();
            });
            Schema::table('customers', function (Blueprint $table) {
                $table->decimal('points', 8, 2)->default(0)->change();
            });
            Schema::table('customer_payments', function (Blueprint $table) {
                $table->decimal('amount', 18, 2)->default(0)->change();
            });
            Schema::table('distributerreqs', function (Blueprint $table) {
                $table->decimal('amount', 8, 2)->default(0)->change();
            });
            Schema::table('distributers', function (Blueprint $table) {
                $table->decimal('rate', 8, 2)->default(0)->change();
                $table->decimal('amount', 8, 2)->default(0)->change();
                $table->decimal('snf_rate', 18, 4)->default(0)->change();
                $table->decimal('fat_rate', 18, 4)->default(0)->change();
                $table->decimal('added_rate', 18, 4)->default(0)->change();
                $table->decimal('fixed_rate', 18, 4)->default(0)->change();
            });
            Schema::table('distributersnffats', function (Blueprint $table) {
                $table->decimal('snf', 18, 2)->default(0)->change();
                $table->decimal('fat', 18, 2)->default(0)->change();
            });
            Schema::table('distributer_milks', function (Blueprint $table) {
                $table->decimal('amount', 18, 2)->default(0)->change();
            });
            Schema::table('distributer_milk_reports', function (Blueprint $table) {
                $table->decimal('milk', 8, 2)->default(0)->change();
                $table->decimal('snf', 8, 2)->default(0)->change();
                $table->decimal('fat', 8, 2)->default(0)->change();
                $table->decimal('rate', 8, 2)->default(0)->change();
                $table->decimal('is_fixed', 8, 2)->default(0)->change();
                $table->decimal('total', 8, 2)->default(0)->change();
            });
            Schema::table('distributorsells', function (Blueprint $table) {
                $table->decimal('rate', 8, 2)->default(0)->change();
                $table->decimal('qty', 8, 2)->default(0)->change();
                $table->decimal('total', 8, 2)->default(0)->change();
                $table->decimal('paid', 8, 2)->default(0)->change();
                $table->decimal('deu', 8, 2)->default(0)->change();
            });
            Schema::table('distributor_payments', function (Blueprint $table) {
                $table->decimal('amount', 8, 2)->default(0)->change();
            });
            Schema::table('employees', function (Blueprint $table) {
                $table->decimal('salary', 8, 2)->default(0)->change();
            });
            Schema::table('employee_advances', function (Blueprint $table) {
                $table->decimal('amount', 18, 2)->default(0)->change();
            });
            Schema::table('employee_reports', function (Blueprint $table) {
                $table->decimal('prevbalance', 18, 2)->default(0)->change();
                $table->decimal('advance', 18, 2)->default(0)->change();
                $table->decimal('salary', 18, 2)->default(0)->change();
                $table->decimal('balance', 18, 2)->default(0)->change();
            });
            Schema::table('expenses', function (Blueprint $table) {
                $table->decimal('amount', 8, 2)->default(0)->change();
            });
            Schema::table('extra_incomes', function (Blueprint $table) {
                $table->decimal('amount', 12, 2)->default(0)->change();
            });
            Schema::table('farmerpayments', function (Blueprint $table) {
                $table->decimal('amount', 8, 2)->default(0)->change();
            });
            Schema::table('farmers', function (Blueprint $table) {
                $table->decimal('rate', 8, 2)->default(0)->change();
                $table->decimal('ts_amount', 8, 2)->default(0)->change();
                $table->decimal('protsahan', 8, 2)->default(0)->change();
                $table->decimal('transport', 8, 2)->default(0)->change();
            });
            Schema::table('farmer_reports', function (Blueprint $table) {
                $table->decimal('milk', 8, 2)->default(0)->change();
                $table->decimal('snf', 8, 2)->default(0)->change();
                $table->decimal('fat', 8, 2)->default(0)->change();
                $table->decimal('rate', 8, 2)->default(0)->change();
                $table->decimal('total', 8, 2)->default(0)->change();
                $table->decimal('due', 8, 2)->default(0)->change();
                $table->decimal('prevdue', 8, 2)->default(0)->change();
                $table->decimal('advance', 8, 2)->default(0)->change();
                $table->decimal('nettotal', 8, 2)->default(0)->change();
                $table->decimal('balance', 8, 2)->default(0)->change();
                $table->decimal('bonus', 18, 2)->default(0)->change();
                $table->decimal('cc', 18, 2)->default(0)->change();
                $table->decimal('tc', 18, 2)->default(0)->change();
                $table->decimal('grandtotal', 8, 2)->default(0)->change();
                $table->decimal('prevbalance', 8, 2)->default(0)->change();
                $table->decimal('paidamount', 8, 2)->default(0)->change();
                $table->decimal('fpaid', 10, 2)->default(0)->change();
                $table->decimal('protsahan_amount', 8, 2)->default(0)->change();
                $table->decimal('transport_amount', 11, 2)->default(0)->change();
            });
            Schema::table('fixed_assets', function (Blueprint $table) {
                $table->decimal('amount', 18, 2)->default(0)->change();
                $table->decimal('depreciation', 8, 2)->default(0)->change();
                $table->decimal('full_amount', 18, 2)->default(0)->change();
                $table->decimal('salvage_amount', 18, 2)->default(0)->change();
                $table->decimal('appreciation', 8, 2)->default(0)->change();
            });
            Schema::table('fixed_asset_categories', function (Blueprint $table) {
                $table->decimal('depreciation', 5, 2)->default(0)->change();
            });
            Schema::table('free_items', function (Blueprint $table) {
                $table->decimal('qty', 12, 2)->default(0)->change();
            });
            Schema::table('items', function (Blueprint $table) {
                $table->decimal('cost_price', 8, 2)->default(0)->change();
                $table->decimal('sell_price', 8, 2)->default(0)->change();
                $table->decimal('stock', 18, 5)->default(0)->change();
                $table->decimal('reward_percentage', 8, 2)->default(0)->change();
                $table->decimal('dis_price', 8, 2)->default(0)->change();
                $table->decimal('tax', 6, 2)->default(0)->change();
                $table->decimal('wholesale', 18, 2)->default(0)->change();
                $table->decimal('points', 8, 2)->default(0)->change();
            });
            Schema::table('item_variants', function (Blueprint $table) {
                $table->decimal('wholesale', 18, 2)->default(0)->change();
                $table->decimal('price', 18, 2)->default(0)->change();
                $table->decimal('ratio', 12, 5)->default(0)->change();
            });
            Schema::table('item_variant_prices', function (Blueprint $table) {
                $table->decimal('wholesale', 18, 2)->default(0)->change();
                $table->decimal('price', 18, 2)->default(0)->change();
            });
            Schema::table('ledgers', function (Blueprint $table) {
                $table->decimal('cr', 8, 2)->default(0)->change();
                $table->decimal('dr', 8, 2)->default(0)->change();
                $table->decimal('amount', 18, 2)->default(0)->change();
            });
            Schema::table('manufactured_products', function (Blueprint $table) {
                $table->decimal('amount', 18, 3)->default(0)->change();
            });
            Schema::table('manufactured_product_items', function (Blueprint $table) {
                $table->decimal('amount', 18, 3)->default(0)->change();
            });
            Schema::table('manufactureitems', function (Blueprint $table) {
                $table->decimal('req_qty', 10, 2)->default(0)->change();
            });
            Schema::table('manufactures', function (Blueprint $table) {
                $table->decimal('qty', 10, 2)->default(0)->change();
            });
            Schema::table('manufacture_processes', function (Blueprint $table) {
                $table->decimal('expected', 18, 3)->default(0)->change();
                $table->decimal('actual', 18, 3)->default(0)->change();
            });
            Schema::table('manufacture_process_items', function (Blueprint $table) {
                $table->decimal('amount', 18, 3)->default(0)->change();
            });
            Schema::table('manufacture_unused_items', function (Blueprint $table) {
                $table->decimal('amount', 8, 3)->default(0)->change();
            });
            Schema::table('manufacture_wastages', function (Blueprint $table) {
                $table->decimal('amount', 8, 3)->default(0)->change();
                $table->decimal('rate', 12, 2)->default(0)->change();
            });
            Schema::table('milkdatas', function (Blueprint $table) {
                $table->decimal('m_amount', 8, 2)->default(0)->change();
                $table->decimal('e_amount', 8, 2)->default(0)->change();
            });
            Schema::table('milk_payments', function (Blueprint $table) {
                $table->decimal('amount', 8, 2)->default(0)->change();
            });
            Schema::table('offer_items', function (Blueprint $table) {
                $table->decimal('min', 18, 3)->default(0)->change();
                $table->decimal('max', 18, 3)->default(0)->change();
                $table->decimal('buy', 18, 3)->default(0)->change();
                $table->decimal('flat', 18, 3)->default(0)->change();
                $table->decimal('percentage', 18, 3)->default(0)->change();
                $table->decimal('get', 18, 3)->default(0)->change();
            });
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('qty', 8, 2)->default(0)->change();
                $table->decimal('rate', 8, 2)->default(0)->change();
            });
            Schema::table('pos_bills', function (Blueprint $table) {
                $table->decimal('total', 18, 2)->default(0)->change();
                $table->decimal('discount', 18, 2)->default(0)->change();
                $table->decimal('taxable', 18, 2)->default(0)->change();
                $table->decimal('tax', 18, 2)->default(0)->change();
                $table->decimal('grandtotal', 18, 2)->default(0)->change();
                $table->decimal('rounding', 18, 2)->default(0)->change();
                $table->decimal('paid', 18, 2)->default(0)->change();
                $table->decimal('due', 18, 2)->default(0)->change();
                $table->decimal('return', 18, 2)->default(0)->change();
                $table->decimal('points', 8, 2)->default(0)->change();
                $table->decimal('ldiscount', 8, 2)->default(0)->change();
            });
            Schema::table('pos_bill_items', function (Blueprint $table) {
                $table->decimal('rate', 12, 2)->default(0)->change();
                $table->decimal('qty', 18, 5)->default(0)->change();
                $table->decimal('amount', 12, 2)->default(0)->change();
                $table->decimal('total', 12, 2)->default(0)->change();
                $table->decimal('discount', 12, 2)->default(0)->change();
                $table->decimal('taxable', 12, 2)->default(0)->change();
                $table->decimal('tax', 12, 2)->default(0)->change();
                $table->decimal('tax_per', 8, 2)->default(0)->change();
                $table->decimal('conversion_qty', 18, 3)->default(0)->change();
                $table->decimal('conversion_rate', 18, 2)->default(0)->change();
            });
            Schema::table('products', function (Blueprint $table) {
                $table->decimal('price', 8, 2)->default(0)->change();
                $table->decimal('minqty', 8, 2)->default(0)->change();
                $table->decimal('discount', 8, 2)->default(0)->change();
            });
            Schema::table('product_purchases', function (Blueprint $table) {
                $table->decimal('total', 12, 2)->default(0)->change();
            });
            Schema::table('product_purchase_items', function (Blueprint $table) {
                $table->decimal('rate', 8, 2)->default(0)->change();
                $table->decimal('qty', 8, 2)->default(0)->change();
            });
            Schema::table('repackage_items', function (Blueprint $table) {
                $table->decimal('from_amount', 18, 3)->default(0)->change();
                $table->decimal('to_amount', 18, 3)->default(0)->change();
            });
            Schema::table('repackaging_costs', function (Blueprint $table) {
                $table->decimal('amount', 12, 2)->default(0)->change();
            });
            Schema::table('repackaging_materials', function (Blueprint $table) {
                $table->decimal('rate', 12, 2)->default(0)->change();
                $table->decimal('qty', 12, 5)->default(0)->change();
            });
            Schema::table('salary_payments', function (Blueprint $table) {
                $table->decimal('amount', 12, 2)->default(0)->change();
            });
            Schema::table('sellitems', function (Blueprint $table) {
                $table->decimal('total', 8, 2)->default(0)->change();
                $table->decimal('qty', 8, 2)->default(0)->change();
                $table->decimal('rate', 8, 2)->default(0)->change();
                $table->decimal('paid', 8, 2)->default(0)->change();
                $table->decimal('due', 8, 2)->default(0)->change();
            });
            Schema::table('simple_manufacturing_items', function (Blueprint $table) {
                $table->decimal('amount', 12, 5)->default(0)->change();
            });
            Schema::table('snffats', function (Blueprint $table) {
                $table->decimal('snf', 8, 2)->default(0)->change();
                $table->decimal('fat', 8, 2)->default(0)->change();
            });
            Schema::table('stocks', function (Blueprint $table) {
                $table->decimal('opening', 18, 2)->default(0)->change();
                $table->decimal('closing', 18, 2)->default(0)->change();
            });
            Schema::table('stock_out_items', function (Blueprint $table) {
                $table->decimal('amount', 8, 2)->default(0)->change();
            });
            Schema::table('supplierbillitems', function (Blueprint $table) {
                $table->decimal('rate', 8, 2)->default(0)->change();
                $table->decimal('qty', 18, 3)->default(0)->change();
                $table->decimal('remaning', 18, 3)->default(0)->change();
                $table->decimal('conversion_qty', 18, 3)->default(0)->change();
                $table->decimal('conversion_rate', 18, 2)->default(0)->change();
            });
            Schema::table('supplierbills', function (Blueprint $table) {
                $table->decimal('total', 8, 2)->default(0)->change();
                $table->decimal('paid', 8, 2)->default(0)->change();
                $table->decimal('due', 8, 2)->default(0)->change();
                $table->decimal('transport_charge', 10, 2)->default(0)->change();
                $table->decimal('taxable', 18, 2)->default(0)->change();
                $table->decimal('tax', 18, 2)->default(0)->change();
                $table->decimal('discount', 18, 2)->default(0)->change();
            });
            Schema::table('supplierpayments', function (Blueprint $table) {
                $table->decimal('amount', 8, 2)->default(0)->change();
            });
            Schema::table('users', function (Blueprint $table) {
                $table->decimal('amount', 18, 2)->default(0)->change();
            });
        });
    }
}
