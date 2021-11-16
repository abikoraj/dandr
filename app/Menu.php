<?php

namespace App;

class Menu
{
    public static function get()
    {
        return [
            "Farmer" => [
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
                ],
            ],
            "milk" => [
                'code' => '02',
                'link' => null,
                'icon' => 'apps',
                'text' => "Milk Collection",
                'children' => [
                    ["Milk Collection", '02.01', route('admin.milk.index')],
                    ["SNF FAT", '02.04', route('admin.snf-fat.index')],
                ],
            ],
            "item" => [
                'code' => '03',
                'link' => null,
                'icon' => 'apps',
                'text' => "Items",
                'children' => [
                    ["Items", '03.04', route('admin.item.index')],
                ],
            ],
            "distributer" => [
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
        ];
    }
}
