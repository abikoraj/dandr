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
];
