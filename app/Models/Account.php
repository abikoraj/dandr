<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;
    const expenses = [
        'trading' => [
            'all' => [
                '1.01.01' => ['Wages', ''],
                '1.01.02' => ['Carriage'],
                '1.01.03' => ['Direct Expense'],
                '1.01.04' => ['Gas, fuel and power'],
                '1.01.05' => ['Fright, Octori and cartage'],
                '1.01.06' => ['Factory Rent'],
                '1.01.07' => ['Factory Expenses'],
                '1.01.08' => ['Royalty'],
                '1.01.09' => ['Custom and Import Duty'],
            ]
        ],
        'nontrading' => [
            [
                'Management_expences' => [
                    '1.02.01' => ['Salaries'],
                    '1.02.02' => ['Office  rent'],
                    '1.02.03' => ['Printing and stationary'],
                    '1.02.04' => ['Telephone'],
                    '1.02.05' => ['Audit fee'],
                    '1.02.06' => ['Legal charges'],
                    '1.02.07' => ['Electricity charges'],
                    '1.02.08' => ['Maintenance'],
                    '1.02.09' => ['Repair and Renawals'],
                    '1.02.10' => ['Depreciation '],

                ],
                'Selling_distribution_expences' => [
                    '1.03.01' => ['Advertisment'],
                    '1.03.02' => ['Bad Debts'],
                    '1.03.03' => ['Provision for bad debts'],
                    '1.03.04' => ['Selling Comission'],


                ],
                'Financial_expences' => [
                    '1.04.01' => ['bank charges'],
                    '1.04.02' => ['Interest on load'],
                    '1.04.03' => ['Discount Allowed']

                ]
            ]
        ]

    ];
}
