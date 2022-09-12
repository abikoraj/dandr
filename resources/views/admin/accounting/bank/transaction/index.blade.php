@extends('admin.layouts.app')
@section('title',"Accounts")
@section('head-title')
<a href="{{route('admin.accounting.index')}}">Accounting</a>
/ Bank Withdrawl / Deposit
@endsection
@section('content')
<form action="{{route('admin.accounting.bank.transaction.index')}}" method="post" id="load-data" >
    @csrf
    @include('admin.layouts.daterange')
    <div class="py-2">
        <button class="btn btn-primary">
            Load Data
        </button>
    </div>
</form>
<hr>
    <div class="row">
        <div class="col-md-3">
            <input type="checkbox" value="1" class="check-type"> Bank Withdrawl
        </div>
        <div class="col-md-3">
            <input type="checkbox" value="2" class="check-type"> Bank Deposit
        </div>
        <div class="col-md-3">
            <input type="checkbox" value="3" class="check-type"> Bank Transfer
        </div>
    </div>
<hr>
<div >
    <table>
        
    </table>
</div>

@endsection
@section('js')
    <script>
        const types=['Bank Withdrawl','Bank Deposit','Bank Transfer'];
        const banks={!! json_encode($banks) !!};
        function name(params) {
            
        }
        $(document).ready(function () {
            loadData();
        });
    </script>

@endsection
