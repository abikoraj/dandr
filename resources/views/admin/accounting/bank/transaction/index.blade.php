@extends('admin.layouts.app')
@section('title',"Accounts - Bank Transactions")
@section('head-title')
<a href="{{route('admin.accounting.index')}}">Accounting</a>
/ Bank Transactions
@endsection
@section('toobar')
    <a href="{{route('admin.accounting.bank.transaction.add')}}" class="btn btn-primary">Add New Transaction</a>
@endsection
@section('content')
<form action="{{route('admin.accounting.bank.transaction.index')}}" method="post" onsubmit="event.preventDefault();loadData();" id="load-data" >
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
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>
                    Date
                </th>
                <th>
                    Transaction Type
                </th>
                <th>
                    Amount
                </th>
                <th>
                    From Bank
                </th>
                <th>
                    To Bank
                </th>
                <th>
    
                </th>
            </tr>
        </thead>
        <tbody id="data">

        </tbody>
    </table>
</div>

@endsection
@section('js')
    <script>
        var transactions=[];
        const types=['','Bank Withdrawl','Bank Deposit','Bank Transfer'];
        const banks={!! json_encode($banks) !!};
        function loadData() {
            const ele=$('#load-data')[0];
            axios.post(ele.action,new FormData(ele))
            .then((res)=>{
                transactions=res.data;
                render();
            })  
            .catch((err)=>{

            });

        }

        function render(){
            
            let html='';
            $('#data').html(html);
            for (let index = 0; index < transactions.length; index++) {
                const transaction = transactions[index];
                let to_bank_name='';
                let from_bank_name='';
                if(transaction.to_bank_id!=null){
                    to_bank_name=banks.find(o=>o.id==transaction.to_bank_id).name;
                }
                if(transaction.from_bank_id!=null){
                    from_bank_name=banks.find(o=>o.id==transaction.from_bank_id).name;
                }
                const date=toNepaliDate(transaction.date);
                html+=`
                <tr>
                    <th>
                        ${date}
                    </th>
                    <th>
                        ${types[transaction.type]}
                    </th>
                    <th>
                        ${transaction.amount}
                    </th>
                    <th>
                        ${from_bank_name}
                    </th>
                    <th>
                        ${to_bank_name}
                    </th>
                    <th>
        
                    </th>
                </tr>
                `;
            }
            $('#data').html(html);
        }
        $(document).ready(function () {
            $('#type').val(1);
            loadData();
        });
    </script>

@endsection
