<?php
return [
    "farmer" => [
        'use' => ['use_farmer'],
        'code' => '01',
        'children' => [
            "list" => ['use' => [], 'code' => "01.01"],
            "add" => ['use' => [], 'code' => "01.02"],
            "update" => ['use' => [], 'code' => "01.03"],
            "delete" => ['use' => [], 'code' => "01.04"],
            "ledger" => ['use' => [], 'code' => "01.09"],
            "advance" => ['use' => [], 'code' => "01.10"],
            "payment" => ['use' => [], 'code' => "01.05"],
            "acc_opening" => ['use' => [], 'code' => "01.06"],
            "milk_payment" => ['use' => [], 'code' => "01.07"],
            "item_sell" => ['use' => [], 'code' => "01.08"],
            "details" => ['use' => [], 'code' => "01.11"],
            "api_list" => ['use' => [], 'code' => "01.12"],
        ]
    ],
    "milk_collection" => [
        'use' => ['use_milk'],
        'code' => '02',
        'children' => [
            'add' => ['use' => ['singleMilk'], 'code' => '02.01'],
            'update' => ['use' => ['singleMilk'], 'code' => '02.02'],
            'delete' => ['use' => ['singleMilk'], 'code' => '02.03'],
            'SNF_FAT_add' => ['use' => ['singleFatSNF'], 'code' => '02.04'],
            'SNF_FAT_update' => ['use' => ['singleFatSNF'], 'code' => '02.05'],
            'SNF_FAT_delete' => ['use' => ['singleFatSNF'], 'code' => '02.06'],
            'MILK_SNF_FAT_add' => ['use' => ['singleMilkFatSNF'], 'code' => '02.07'],
            'MILK_SNF_FAT_NAME_add' => ['use' => ['multipleMilkFatSNF'], 'code' => '02.08'],
            "push_milk_data" => ['use' => [], 'code' => "02.09"],
            "pull_milk_data" => ['use' => [], 'code' => "02.10"],
            "push_fatsnf" => ['use' => [], 'code' => "02.11"],
            "collection_center" => ['use' => [], 'code' => "02.12"],

        ]
    ],
    "item" => [
        'use' => [],
        'code' => '03',
        'children' => [
            'add' => ['use' => [], 'code' => '03.01'],
            'update' => ['use' => [], 'code' => '03.02'],
            'delete' => ['use' => [], 'code' => '03.03'],
            'list' => ['use' => [], 'code' => '03.04'],
            'stock' => ['use' => [], 'code' => '03.05'],
            // 'stock_out' => ['use'=>[],'code' => '03.06'],
            'branch_stock' => ['use' => [], 'code' => '03.07'],
            'repackaging' => ['use' => [], 'code' => '03.08'],
            'wastage' => ['use' => [], 'code' => '03.09'],
            'manage_variants' => ['use' => [], 'code' => '03.10'],
            'stock_tracking' => ['use' => [], 'code' => '03.11'],
        ]
    ],
    "distributer" => [
        'use' => ['use_distributer'],
        'code' => '04',
        'children' => [
            "list" => ['use' => [], 'code' => "04.01"],
            "add" => ['use' => [], 'code' => "04.02"],
            "update" => ['use' => [], 'code' => "04.03"],
            "delete" => ['use' => [], 'code' => "04.04"],
            "payment" => ['use' => [], 'code' => "04.05"],
            "acc_opening" => ['use' => [], 'code' => "04.06"],
            "milk_collection" => ['use' => [], 'code' => "04.07"],
            "SNF_FAT" => ['use' => [], 'code' => "04.08"],
            "item_sell" => ['use' => [], 'code' => "04.09"],
            "credit_list" => ['use' => [], 'code' => "04.10"],
            "ledger" => ['use' => [], 'code' => "04.11"],
        ]
    ],

    "staff" => [
        'use' => ['use_employee'],
        'code' => '05',
        'children' => [
            'employee list' => ['use' => [], 'code' => '05.01'],
            'employee add' => ['use' => [], 'code' => '05.02'],
            'employee edit' => ['use' => [], 'code' => '05.03'],
            'employee delete' => ['use' => [], 'code' => '05.04'],
            'account opening' => ['use' => [], 'code' => '05.05'],
            'advance' => ['use' => [], 'code' => '05.06'],
            'salary' => ['use' => [], 'code' => '05.07'],
            'advance return' => ['use' => [], 'code' => '05.08'],
            'sales' => ['use' => [], 'code' => '05.09'],
            'sales delete' => ['use' => [], 'code' => '05.10'],
        ]
    ],

    "manage_expense" => [
        'use' => ['use_expense'],
        'code' => '06',
        'children' => [
            'category list' => ['use' => [], 'code' => '06.01'],
            'category add' => ['use' => [], 'code' => '06.02'],
            'category edit' => ['use' => [], 'code' => '06.03'],
            'category delete' => ['use' => [], 'code' => '06.04'],
            'expense list' => ['use' => [], 'code' => '06.05'],
            'expense add' => ['use' => [], 'code' => '06.06'],
            'expense edit' => ['use' => [], 'code' => '06.07'],
            'expense delete' => ['use' => [], 'code' => '06.08'],
        ]
    ],

    "suppliers" => [
        'use' => ['use_supplier'],
        'code' => '07',
        'children' => [
            'supplier list' => ['use' => [], 'code' => '07.01'],
            'supplier add' => ['use' => [], 'code' => '07.02'],
            'supplier edit' => ['use' => [], 'code' => '07.03'],
            'supplier delete' => ['use' => [], 'code' => '07.04'],
            'purchase bills' => ['use' => [], 'code' => '07.05'],
            'purchase add' => ['use' => [], 'code' => '07.06'],
            'purchase edit' => ['use' => [], 'code' => '07.07'],
            'purchase delete' => ['use' => [], 'code' => '07.08'],
            'Supplier Payment' => ['use' => [], 'code' => '07.09'],
            'Opening Balance' => ['use' => [], 'code' => '07.10'],
            'Supplier Payment Delete' => ['use' => [], 'code' => '07.11'],
        ]
    ],

    "customer" => [
        'use' => ['use_restaurant', 'use_pos'],
        'code' => '08',
        'children' => [
            'customer list' => ['use' => [], 'code' => '08.01'],
            'payment' => ['use' => [], 'code' => '08.02'],
        ]
    ],

    "Manufacture" => [
        'use' => ['use_manufacture'],
        'code' => '13',
        'children' => [
            'product_template' => ['use' => [], 'code' => '13.01'],
            'list_process' => ['use' => [], 'code' => '13.02'],
            'add_process' => ['use' => [], 'code' => '13.03'],
            'manage_process' => ['use' => ['use_full_manufacture'], 'code' => '13.04'],
            'manage_process' => ['use' => ['use_simple_manufacture'], 'code' => '13.06'],
            'api_list_process_api' => ['use' => [], 'code' => '13.05'],
            'api_list_process_api' => ['use' => [], 'code' => '13.05'],
        ]
    ],

    "POS" => [
        'use' => ['use_pos'],
        'code' => '09',
        'children' => [
            'POS Interface' => ['use' => [], 'code' => '09.01'],
            'Search Bills' => ['use' => [], 'code' => '09.02'],
            'Reprint Bills' => ['use' => [], 'code' => '09.03'],
            'Sales Returns' => ['use' => [], 'code' => '09.04'],
            'sync' => ['use' => [], 'code' => '09.05'],
        ]
    ],
    "Old Pos" => [
        'use' => ['use_oldpos', 'use_restaurant'],
        'code' => '15',
        'children' => [
            'add manage' => ['use' => [], 'code' => '15.01'],
            'list' => ['use' => [], 'code' => '15.02'],
        ]
    ],

    "POS_Setting" => [
        'use' => ['use_pos'],
        'code' => '10',
        'children' => [
            'Day Management' => ['use' => [], 'code' => '10.01'],
            'Counters' => ['use' => [], 'code' => '10.02'],
            'Offers' => ['use' => [], 'code' => '10.03'],
            'Reward_Point_setting' => ['use' => [], 'code' => '10.03'],
        ]
    ],

    "Payment_Setting" => [
        'use' => ['use_pos'],
        'code' => '11',
        'children' => [
            'Banks' => ['use' => [], 'code' => '11.01'],
            'Payments Gateways' => ['use' => [], 'code' => '11.02'],
        ]
    ],

    "Reports" => [
        'use' => ['use_reports'],

        'code' => '12',
        'children' => [
            'Reports' => ['use' => [], 'code' => '12.01'],
        ]
    ],

    "Restaurant" => [
        'use' => ['use_restaurant'],
        'code' => '14',
        'children' => [
            'Manage Tables' => ['use' => [], 'code' => '14.01'],
            'Manage Orders' => ['use' => [], 'code' => '14.02'],
        ]
    ]


];
