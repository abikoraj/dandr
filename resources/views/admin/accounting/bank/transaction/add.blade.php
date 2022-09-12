@extends('admin.layouts.app')
@section('title', 'Accounts - Add Bank Transactions ')
@section('head-title')
    <a href="{{ route('admin.accounting.index') }}">Accounting</a>
    / <a href="{{ route('admin.accounting.bank.transaction.index') }}">Bank Transactions</a>
    / Add
@endsection
@section('content')
    <form action="{{route('admin.accounting.bank.transaction.add')}}" method="post" onsubmit="return saveData(this,event);">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <label for="date">Date</label>
                <input type="text" name="date" id="date" class="form-control calender" required>
            </div>
            <div class="col-md-3 mb-2">
                <label for="type">Transaction Type</label>
                <select name="type" id="type" class="form-control ms" onchange="typeChanged(this)" required>
                    <option value="1">Cash withdrawl from bank</option>
                    <option value="2">Cash deposit to bank</option>
                    <option value="3">Bank Transfer</option>
                </select>
            </div>
            <div class="col-md-3 mb-2 from_bank">
                <label for="from_bank_id">Bank Account</label>
                <select name="from_bank_id" id="from_bank_id" class="form-control ms" required>
                    @foreach ($banks as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2 to_bank " style="display: none;">
                <label for="to_bank_id">Transfer To </label>
                <select name="to_bank_id" id="to_bank_id" class="form-control ms">
                    @foreach ($banks as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label for="amount">Amount</label>
                <input type="number" min="0" class="form-control" name="amount" id="amount"
                    placeholder="Enter Transaction Amount" required>
            </div>
            <div class="col-md-3 mb-2">
                <label for="number">Cheque/Slip Number</label>
                <input type="text" name="number" id="number" class="form-control" >
            </div>
            <div class="col-md-3 mb-2">
                <label for="transaction_by">Transaction By</label>
                <input type="text" name="transaction_by" id="transaction_by" class="form-control" required> 

            </div>
            <div class="col-md-12 mb-2">
                <label for="remark">Remarks</label>
                <input type="text" name="remarks" id="remarks" class="form-control" required>
            </div>
            <div class="col-md-12">
                <button class="btn btn-primary">Add Transaction</button>
            </div>
        </div>
    </form>
@endsection
@section('js')
    <script>
        function typeChanged(ele) {
            if (ele.value == 3) {
                $('.to_bank').show();
            } else {
                $('.to_bank').hide();
            }
        }

        
        function saveData(ele,e){
            e.preventDefault();
            const type=$('#type').val();
            if(type==3){
                const from_bank=$('#from_bank_id').val();
                const to_bank=$('#to_bank_id').val();
                if(from_bank==to_bank){
                    alert('Destination and transferring bank cannot be same');
                    return;
                }
                
            }   

            const amount= parseFloat($('#amount').val());
            if(isNaN(amount) || amount==0){
                alert('Amount should be greater than zero');
                return;
            }

            showProgress('Saving Transaction');
            axios.post(ele.action,new FormData(ele))
            .then((res)=>{
                hideProgress();
                ele.reset();
                typeChanged(document.getElementById('type'));
            })
            .catch((err)=>{
                hideProgress();

                if(err.response){
                    alert(err.response.data.message);
                }else{
                    alert('Some error occured please try again');
                }
            })
        }

    </script>
@endsection
