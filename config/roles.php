<?php
return [
    "farmer" => [
        'code' => '01',
        'children' => [
            "list" => ['code' => "01.01"],
            "add" => ['code' => "01.02"],
            "update" => ['code' => "01.03"],
            "delete" => ['code' => "01.04"],
            "ledger" => ['code' => "01.09"],
            "advance" => ['code' => "01.10"],
            "payment" => ['code' => "01.05"],
            "acc_opening" => ['code' => "01.06"],
            "milk_payment" => ['code' => "01.07"],
            "item_sell" => ['code' => "01.08"],
        ]
    ],
    "milk_collection" => [
        'code' => '02',
        'children' => [
            'add' => ['code' => '02.01'],
            'update' => ['code' => '02.02'],
            'delete' => ['code' => '02.03'],
            'SNF_FAT_add' => ['code' => '02.04'],
            'SNF_FAT_update' => ['code' => '02.05'],
            'SNF_FAT_delete' => ['code' => '02.06'],
        ]
    ],
    "item" => [
        'code' => '03',
        'children' => [
            'add' => ['code' => '03.01'],
            'update' => ['code' => '03.02'],
            'delete' => ['code' => '03.03'],
            'list' => ['code' => '03.04'],
            'stock' => ['code' => '03.05'],
            // 'stock_out' => ['code' => '03.06'],
            'branch_stock' => ['code' => '03.07'],
            'repackaging' => ['code' => '03.08'],
            'wastage' => ['code' => '03.09'],
            'manage_variants' => ['code' => '03.10'],
        ]
    ],
    "distributer" => [
        'code' => '04',
        'children' => [
            "list" => ['code' => "04.01"],
            "add" => ['code' => "04.02"],
            "update" => ['code' => "04.03"],
            "delete" => ['code' => "04.04"],
            "payment" => ['code' => "04.05"],
            "acc_opening" => ['code' => "04.06"],
            "milk_collection" => ['code' => "04.07"],
            "SNF_FAT" => ['code' => "04.08"],
            "item_sell" => ['code' => "04.09"],
            "credit_list" => ['code' => "04.10"],
            "ledger" => ['code' => "04.11"],
        ]
    ],

    "staff" => [
        'code' => '05',
        'children' => [
            'employee list' => ['code' => '05.01'],
            'employee add' => ['code' => '05.02'],
            'employee edit' => ['code' => '05.03'],
            'employee delete' => ['code' => '05.04'],
            'account opening' => ['code' => '05.05'],
            'advance' => ['code' => '05.06'],
            'salary' => ['code' => '05.07'],
            'advance return' => ['code' => '05.08'],
        ]
    ],

    "manage_expense" => [
        'code' => '06',
        'children' => [
            'category list' => ['code' => '06.01'],
            'category add' => ['code' => '06.02'],
            'category edit' => ['code' => '06.03'],
            'category delete' => ['code' => '06.04'],
            'expense list' => ['code' => '06.05'],
            'expense add' => ['code' => '06.06'],
            'expense edit' => ['code' => '06.07'],
            'expense delete' => ['code' => '06.08'],
        ]
    ],

    "suppliers" => [
        'code' => '07',
        'children' => [
            'supplier list' => ['code' => '07.01'],
            'supplier add' => ['code' => '07.02'],
            'supplier edit' => ['code' => '07.03'],
            'supplier delete' => ['code' => '07.04'],
            'purchase bills' => ['code' => '07.05'],
            'purchase add' => ['code' => '07.06'],
            'purchase edit' => ['code' => '07.07'],
            'purchase delete' => ['code' => '07.08'],
            'Supplier Payment' => ['code' => '07.09'],
            'Opening Balance' => ['code' => '07.10'],
            'Supplier Payment Delete' => ['code' => '07.11'],
        ]
    ],

    "customer" => [
        'code' => '08',
        'children' => [
            'customer list' => ['code' => '08.01'],
            'payment' => ['code' => '08.02'],
        ]
    ],

    "Manufacture" => [
        'code' => '13',
        'children' => [
            'product_list' => ['code' => '08.01'],
            'payment' => ['code' => '08.02'],
        ]
    ],

    "POS" => [
        'code' => '09',
        'children' => [
            'POS Interface' => ['code' => '09.01'],
            'Search Bills' => ['code' => '09.02'],
            'Reprint Bills' => ['code' => '09.03'],
            'Sales Returns' => ['code' => '09.04'],
            'sync' => ['code' => '09.05'],
        ]
    ],

    "POS_Setting" => [
        'code' => '10',
        'children' => [
            'Day Management' => ['code' => '10.01'],
            'Counters' => ['code' => '10.02'],
            'Offers' => ['code' => '10.03'],
            'Reward_Point_setting' => ['code' => '10.03'],
        ]
    ],

    "Payment_Setting" => [
        'code' => '11',
        'children' => [
            'Banks' => ['code' => '11.01'],
            'Payments Gateways' => ['code' => '11.02'],
        ]
    ],

    "Reports" => [
        'code' => '12',
        'children' => [
            'Reports' => ['code' => '12.01'],
        ]
    ],


];
