@extends('admin.layouts.app')
@section('title','Counters')
@section('head-title','Counters')
@section('toobar')
<button type="button" class="btn btn-primary waves-effect m-r-20" data-toggle="modal" data-target="#addModal">New Counter</button>
@endsection
@section('content')

@include('admin.counter.add')
{{-- @include('admin.customer.edit') --}}
<div >
    <div class="row" id="data">
        @foreach ($counters as $counter)
            @include('admin.counter.single',['counter'=>$counter])
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
                showProgress('Updating Customer');
                var data={'id':id};
                axios.post('{{route('admin.customer.del')}}',data)
                .then((res)=>{
                   
                    $('#customer_'+id).replaceWith(res.data);
                    hideProgress();
                    lock=false;
                    $('#editModal').modal('hide');
                    document.getElementById('editCustomer').reset();
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
