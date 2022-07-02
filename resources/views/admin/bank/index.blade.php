@extends('admin.layouts.app')
@section('title')
    Bank Ac ( {{$account->fiscalyear->name}} ) / Accounts
@endsection
@section('head-title')
<a href="{{route('admin.accounting.index')}}">Accounting</a>
/ <a href="{{route('admin.accounting.accounts')}}">Accounts</a>
/ Bank Ac ( {{$account->fiscalyear->name}} ) / Accounts
@endsection
@section('toobar')
<button type="button" class="btn btn-primary waves-effect mr-2" data-toggle="modal" data-target="#addModal">Add New Bank Account</button>
@endsection
@section('content')

@include('admin.bank.add')
{{-- @include('admin.customer.edit') --}}
<div >
    <div class="row" id="data">
        @foreach ($banks as $bank)
            @include('admin.bank.single',['bank'=>$bank])
        @endforeach
    </div>

</div>
@endsection
@section('js')
<script>

    lock=false;
    function updateBank(e,ele){
        e.preventDefault();

        showProgress('Updating Bank Account');
        axios.post(ele.action,new FormData(ele))
        .then((res)=>{
            hideProgress();
            showNotification('bg-success',"Bank account updated.");
        })
        .catch((err)=>{
            hideProgress();
            showNotification('bg-danger',"Error while updating bank account.");
        });
    }

    function del(id){
        if(!lock){
            if(prompt('Please Type Yes To Delete').toLowerCase()=="yes"){

                lock=true;
                showProgress('Deleting Bank ');
                var data={'id':id};
                axios.post('{{route('admin.bank.delete')}}',data)
                .then((res)=>{

                    $('#bank-'+id).remove();
                    hideProgress();
                    lock=false;

                })
                .catch((err)=>{
                    hideProgress();
                    lock=false;
                })
            }
            }
    }
</script>
@endsection
