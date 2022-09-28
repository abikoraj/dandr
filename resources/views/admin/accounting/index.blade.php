@extends('admin.layouts.app')
@section('content')
@section('head-title', 'Accounting')
    <div class="row">
        {{-- <div class="col-md-2 section href" data-target="{{route('admin.accounting.stock.index')}}">
            <span class="icon">
                <i class="zmdi zmdi-accounts"></i>
            </span>
            <span class="divider"></span>
            <span class="text">
               Stocks
            </span>
        </div> --}}
        <div class="col-md-2 section href" data-target="{{route('admin.accounting.accounts.index')}}">
            <span class="icon">
                <i class="zmdi zmdi-accounts"></i>
            </span>
            <span class="divider"></span>
            <span class="text">
               Assets & <br> Liabilities
            </span>
        </div>
        <div class="col-md-2 section href" data-target="{{route('admin.accounting.final')}}">
            <span class="icon">
                <i class="zmdi zmdi-accounts"></i>
            </span>
            <span class="divider"></span>
            <span class="text">
               Final Accounts
            </span>
        </div>
        <div class="col-md-2 section href" data-target="{{route('admin.accounting.extra.income.index')}}">
            <span class="icon">
                <i class="zmdi zmdi-accounts"></i>
            </span>
            <span class="divider"></span>
            <span class="text">
               Extra incomes
            </span>
        </div>
        <div class="col-md-2 section href" data-target="{{route('admin.accounting.opening.index')}}">
            <span class="icon">
                <i class="zmdi zmdi-accounts"></i>
            </span>
            <span class="divider"></span>
            <span class="text">
               Opening
            </span>
        </div>
        <div class="col-md-2 section href" data-target="{{route('admin.accounting.bank.transaction.index')}}">
            <span class="icon">
                <i class="zmdi zmdi-accounts"></i>
            </span>
            <span class="divider"></span>
            <span class="text">
               Bank Transactions
            </span>
        </div>
    </div>
@endsection
