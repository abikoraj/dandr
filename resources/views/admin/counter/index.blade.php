@extends('admin.layouts.app')
@section('title','Counters')
@section('head-title','Counters')
@section('toobar')
<button type="button" class="btn btn-primary waves-effect mr-2" data-toggle="modal" data-target="#addModal">New Counter</button>
<a href="{{route('admin.counter.day.index')}}" class="btn btn-primary">Day Management</a>
@endsection
@section('content')

@include('admin.counter.add')
<div class="row">
    <div class="col-md-4">
        <label for="day">Date</label>
        <input type="text" name="day" id="day" class="calender form-control">
    </div>
    <div class="col-md-4 d-flex align-items-end">
        <button class="btn btn-primary" onclick="refreshCounters();">View Data</button>
    </div>
</div>
<hr>
<div >
    <div class="row" id="data">

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
                showProgress('Deleting Counter');
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

    function refreshCounter(url,id){
        showProgress('Refreshing');
            axios.get(url)
            .then((res)=>{
                $('#status-'+id).html(res.data);
                hideProgress();
            })
            .catch((err)=>{
                hideProgress();

            });

    }

    function reopenCounter(url,id){
        if(prompt("Enter YES to continue").toLowerCase()=="yes"){

        showProgress('Opening counter');
            axios.get(url)
            .then((res)=>{
                $('#status-'+id).html(res.data);
                hideProgress();
            })
            .catch((err)=>{
                hideProgress();

            });
        }
    }

    window.onload=()=>{
        refreshCounters();
    };


    function refreshCounters(){
            showProgress('Loading');

            axios.post("{{route('admin.counter.list')}}",{"date":$('#day').val()})
            .then((res)=>{
                $('#data').html(res.data);
                hideProgress();
            })
            .catch((err)=>{
                hideProgress();

            });

    }
</script>
@endsection
