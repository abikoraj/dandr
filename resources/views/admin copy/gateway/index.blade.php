@extends('admin.layouts.app')
@section('title','Paymemt Gateways')
@section('head-title','Paymemt Gateways')
@section('toobar')
<button type="button" class="btn btn-primary waves-effect mr-2" data-toggle="modal" data-target="#addModal">New Gateway</button> 
@endsection
@section('content')

@include('admin.gateway.add')
{{-- @include('admin.customer.edit') --}}
<div >
    <div class="row" id="data">
        @foreach ($gateways as $gateway)
            @include('admin.gateway.single',['gateway'=>$gateway])
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
                showProgress('Deleting Gateway ');
                var data={'id':id};
                axios.post('{{route('admin.gateway.delete')}}',data)
                .then((res)=>{
                   
                    $('#gateway-'+id).remove();
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
