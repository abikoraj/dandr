@extends('admin.layouts.app')
@section('title','Banks')
@section('head-title','Banks')
@section('toobar')
<button type="button" class="btn btn-primary waves-effect mr-2" data-toggle="modal" data-target="#addModal">New Bank</button> 
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
