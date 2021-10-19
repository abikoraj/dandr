<?php

namespace App\IRD;

class BillReturnViewModel
{
    public  $username;
    public  $password;
    public  $seller_pan;
    public  $buyer_pan;
    public  $fiscal_year;
    public  $buyer_name;
    public  $invoice_number;
    public  $invoice_date;
    public  $total_sales;
    public  $taxable_sales_vat;
    public  $vat;
    public  $excisable_amount;
    public  $excise;
    public  $taxable_sales_hst;
    public  $hst;
    public  $amount_for_esf;
    public  $esf;
    public  $export_sales;
    public  $tax_exempted_sales;
    public bool $isrealtime;
    public  $datetimeClient;
}
