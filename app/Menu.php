<?php

namespace App;

class Menu
{
    public static function get()
    {
        return [
            "Farmer" => [
                'condition'=>env('use_farmer',false),
                'code' => '01',
                'link' => null,
                'icon' => 'apps',
                'text' => "Farmer",
                'children' => [
                    ["Farmer List", '01.01', route('admin.farmer.list')],
                    ["Advance", '01.10', route('admin.farmer.advance')],
                    ["Farmer Payment", '01.05', route('admin.farmer.due')],
                    ["Account Opening", '01.06', route('admin.farmer.due.add.list')],
                    ["Milk Payment", '01.07', route('admin.farmer.milk.payment.index')],
                    ["Farmer Sell", '01.08', route('admin.sell.item.index')],
                    ["Check Passbook", '01.11', route('admin.farmer.passbook.index')],
                    ["Enable Disable", '01.11', route('admin.farmer.switch')],
                    ["Print Slip", '01.11', route('admin.farmer.printSlip')],
                ],
            ],
            "milk" => [
                'condition'=>env('use_milk',false),
                'code' => '02',
                'link' => null,
                'icon' => 'apps',
                'text' => "Milk Collection",
                'children' => [
                    ["Manage Collection Center", '02.12', route('admin.center.index')],
                    ["Milk Collection", '02.01', route('admin.milk.index')],
                    ["SNF FAT", '02.04', route('admin.snf-fat.index')],
                    ["Milk and Fat SNF", '02.07', route('admin.milk.milkfatsnf')],
                    ["Milk and Fat SNF With Name", '02.08', route('admin.milk.milkfatsnfname')],
                ],
            ],
            "item" => [
                'condition'=>env('use_farmer',false) || env('use_farmer') || env('use_pos'),
                'code' => '03',
                'link' => null,
                'icon' => 'apps',
                'text' => "Items",
                'children' => [
                    ["Items", '03.04', route('admin.item.index')],
                    ["Stock Out", '03.05', route('admin.item.stockout-list')],
                    ["Branch Stock", '03.07', route('admin.item.items-center-stock')],
                    ["Repackaging", '03.08', route('admin.item.packaging.index')],
                    ["Wastage", '03.09', route('admin.wastage.index')],
                    ["Stock Tracking", '03.11', route('admin.item.stock.tracking')],
                    ["Cheese Batch Management", '03.12', route('admin.items')],
                ],
            ],
            "distributer" => [
                'condition'=>env('use_distributer',false),

                'code' => '04',
                'link' => null,
                'icon' => 'apps',
                'text' => "Distributers",
                'children' => [
                    ["Distributer List", '04.01', route('admin.distributer.index')],
                    ["Distributer Sell", '04.09', route('admin.distributer.sell')],
                    ["Distributer Payment", '04.05', route('admin.distributer.payemnt')],
                    ["Account Opening", '04.06', route('admin.distributer.detail.opening')],
                    ["Credit List", '04.10', route('admin.distributer.credit.list')],
                    ["SNF FAT", '04.08', route('admin.distributer.snffat.index')],
                    ["Milk Collection", '04.07', route('admin.distributer.MilkData.index')],
                ],
            ],
            "staff" => [
                'condition'=>env('use_employee',false),
                'code' => '05',
                'link' => null,
                'icon' => 'apps',
                'text' => "Staff Manage",
                'children' => [
                    ["Employees", '05.01', route('admin.employee.index')],
                    ["Account Opening", '05.05', route('admin.employee.account.index')],
                    ["Advance", '05.06', route('admin.employee.advance')],
                    ["Salary Pay", '05.07', route('admin.salary.pay')],
                    ["Sales", '05.09', route('admin.employee.sales.index')],
                    ["Advance Return", '05.08', route('admin.employee.ret')],
                ],
            ],

            "expese" => [
                'condition'=>env('use_expense',false),
                
                'code' => '06',
                'link' => null,
                'icon' => 'apps',
                'text' => "Manage Expense",
                'children' => [
                    ["Expense Categories", '06.01', route('admin.expense.category')],
                    ["Expenses", '06.05', route('admin.expense.index')],
                ],
            ],

            "supplier" => [
                'condition'=>env('use_supplier',false),
                
                'code' => '07',
                'link' => null,
                'icon' => 'apps',
                'text' => "Suppliers",
                'children' => [
                    ["Supplier List", '07.01', route('admin.supplier.index')],
                    ["Purchase Bill", '07.05', route('admin.supplier.bill')],
                    ["Supplier Payment", '07.09', route('admin.supplier.pay')],
                    ["Opening Balance", '07.10', route('admin.supplier.previous.balance')],
                ],
            ],

            "customer" => [
                'condition'=>env('use_pos',false) || env('use_restaurant'),
                'code' => '08',
                'link' => null,
                'icon' => 'apps',
                'text' => "Customers",
                'children' => [
                    ["List", '08.01', route('admin.customer.home')],
                    ["Payment", '08.02', route('admin.customer.payment.index')],
                    ["Opening", '08.03', route('admin.customer.opening')],
                ],
            ],
            "manufacture" => [
                'condition'=>env('user_manufacture',false),

                'code' => '08',
                'link' => null,
                'icon' => 'apps',
                'text' => "Manufacture",
                'children' => [
                    ["Manage Products", '13.01', route('admin.manufacture.product.index')],
                    ["Manage Process", '13.02', route('admin.manufacture.process.index')],
                    ["Manage Process", '13.06', route('admin.simple.manufacture.index')],
                ],
            ],
            "Restaurant"=>[
                'condition'=>env('use_restaurant',false),
                
                'code' => '14',
                'link' => null,
                'icon' => 'apps',
                'text' => "Restaurant",
                'children' => [
                    ["Manage Tables", '14.01', route('admin.table.index')],
                    ["Table Orders", '14.02', route('restaurant.table')],
                ],  
            ],
            "old_pos"=>[
                'condition'=>env('use_oldpos',false),
                'code' => '15',
                'link' => null,
                'icon' => 'apps',
                'text' => "Billing Records",
                'children' => [
                    ["Interface", '15.01', route('admin.billing.home')],
                    ["Search Bill", '15.02', route('admin.billing.list')],
                ],  
            ],
            "pos" => [
                'condition'=>env('use_pos',false),

                'code' => '09',
                'link' => null,
                'icon' => 'apps',
                'text' => "POS",
                'children' => [

                    ["POS Interface Old", '09.01', route('admin.billing.home')],

                    ["POS Interface", '09.01', url('/pos/day')],
                    ["Search Bills", '09.02', route('admin.pos.billing.index')],
                    ["Reprint Bills", '09.03', route('admin.pos.billing.print')],
                    ["Sales Returns", '09.04', route('admin.pos.billing.return')],
                ],
            ],


            "pos setting" => [
                'condition'=>env('use_pos',false),

                'code' => '10',
                'link' => null,
                'icon' => 'apps',
                'text' => "POS Setting",
                'children' => [
                    ["Day Management", '10.01', route('admin.counter.day.index')],
                    ["Counters", '10.02', route('admin.counter.home')],
                    ["Offers", '10.03', route('admin.offers.index')],
                    ["Offers", '10.04', route('admin.point.index')],
                ],
            ],

            "Payment setting" => [
                'condition'=>env('use_pos',false),

                'code' => '11',
                'link' => null,
                'icon' => 'apps',
                'text' => "Payment Setting",
                'children' => [
                    ["Payment Gateways", '11.02', route('admin.gateway.index')],
                ],
            ],

            "Reports" => [
                'condition'=>env('use_reports',false),
                
                'code' => '12',
                'link' => null,
                'icon' => 'apps',
                'text' => "Reports",
                'children' => [
                    ["Reports", '12.01', route('admin.report.home')],
                ],
            ],

        ];
    }
}
