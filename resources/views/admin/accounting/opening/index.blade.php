@extends('admin.layouts.app')
@section('title',"Accounts - Opening")
@section('head-title')
<a href="{{route('admin.accounting.index')}}">Accounting</a>
/ Accounts / {{$fy->name}} / Openings
@endsection
@section('content')
<form action="{{route('admin.accounting.opening.index')}}" id="add" >
    @csrf
    <div class="row">
        <div class="col-md-3">
            <label for="date">Date</label>
            <input type="text" name="date" id="date" value="{{$fy->startdate}}" class="calender form-control" required> 
        </div>
        <div class="col-md-3">
            <label for="account_id">Account</label>
            <select name="account_id" id="account_id" class="form-control ms" required>
                @foreach ($showAccounts as $account)
                    
                    <option id="acc_{{$account->id}}" value="{{$account->id}}">{{$account->name}} ( {{$account->identifire}} )</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="amount">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" required>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100">
                Open Account
            </button>
        </div>
    </div>
</form>
<hr>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>
                Account
            </th>
            <th>
                Opening Balance
            </th>
        </tr>
    </thead>
    <tbody id="data">

        @foreach ($openings as $opening)
            @php
                $acc=$accounts->where('id',$opening->account_id)->first();
            @endphp
            @include('admin.accounting.opening.single',['acc'=>$acc,'opening'=>$opening])
            
        @endforeach
    </tbody>
</table>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $('#add').submit(function (e) {
                const account_id= $('#account_id').val();
                e.preventDefault();
                showProgress('Opening Amount');
                axios.post('{{route('admin.accounting.opening.index')}}',new FormData(this))
                .then((res)=>{
                    hideProgress();
                    $('#acc_'+account_id).remove();
                    $('#data').append(res.data);
                    $('#amount').val(null).change();
                    console.log(res.data);
                })
                .catch((err)=>{
                    hideProgress();

                    if(err.response){
                        alert(err.response.data.message);
                    }else{
                        alert('Some Error Occured Please Try Again');
                    }
                })
            });
        });
    </script>
@endsection