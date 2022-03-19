@extends('admin.layouts.app')
@section('title','Distributer Balance')
@section('css')
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
<style>
    td,th{
            border:1px solid black !important;
        }
        table{
            width:100%;
            border-collapse: collapse;
        }
        thead {display: table-header-group;}
        tfoot {display: table-header-group;}
</style>
@endsection
@section('head-title','Distributer Balance')
@section('toobar')

@endsection
@section('content')
<div class="row">
    {{-- @include('admin.distributer.sell.productmodal') --}}
    <div class="col-md-3">
        <div>
            <input type="hidden" id="currentdate">
            <input type="text" placeholder="Search Distributor" id="s_dis" class="form-control mb-3">
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                </tr>
            </thead>
            <tbody id="distributors">
                @foreach(\App\Models\Distributer::get() as $d)
                @if ($d->user!=null)
                    <tr style="cursor:pointer;" onclick="setData('id',{{$d->id}})" id="dis-{{$d->id}}" data-name="{{ $d->user->name }}" class="searchable">
                        <td>
                            {{$d->id}}
                        </td>
                        <td>
                            {{$d->user->name}}
                        </td>
                    </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-md-9 bg-light pt-2">
        <form action="" id="sellitemData">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input readonly type="text" name="date" id="nepali-datepicker" class="calender form-control next" data-next="user_id" placeholder="Date" onchange="">
                    </div>
                </div>
            </div>
            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="id">Distributer</label>
                        <input type="number" id="id" name="id" class="form-control next" data-next="product_id">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" id="amount" name="amount" class="form-control next" data-next="product_id">

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="type">Type</label>
                        <select name="type" id="type"  class="form-control show-tick ms ">
                            <option value="1">CR</option>
                            <option value="2">DR</option>
                        </select>

                    </div>
                </div>

                <div class="col-md-3 mt-4">
                    <input type="button" value="Save" class="btn btn-primary btn-block" onclick="saveData();" id="save">
                    {{-- <span class="btn btn-primary btn-block" >Save</span> --}}
                </div>

            </div>
        </form>
        <div class="row">
            <div class="col-md-12">
                <div class="mt-5">
                    <div class="pt-2 pb-2">
                        <input type="text" id="sId" placeholder="Search" style="width: 200px;">
                    </div>
                    <table id="newstable1" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="sellDisDataBody">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- edit modal -->

{{-- @include('admin.distributer.sell.editmodal') --}}
@endsection
@section('js')
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/pages/forms/advanced-form-elements.js') }}"></script>
<script src="{{ asset('calender/nepali.datepicker.v3.2.min.js') }}"></script>
<script>
    // $( "#x" ).prop( "disabled", true );
    initTableSearch('sId', 'sellDisDataBody', ['name']);
    initTableSearch('s_dis', 'distributors', ['name']);


    function saveData() {
        if ($('#nepali-datepicker').val() == '' || $('#id').val() == '' || $('#amount').val()=="" || $('#amount').val()=="0") {
            alert('Please enter data in empty field !');
            $('#id').focus();
            return false;
        } else {
            var bodyFormData = new FormData(document.getElementById('sellitemData'));
            axios({
                    method: 'post',
                    url: '{{ route("admin.distributer.detail.ledger")}}',
                    data: bodyFormData,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function(response) {
                    console.log(response.data);
                    showNotification('bg-success', 'Sellitem added successfully !');
                    $('#sellDisDataBody').prepend(response.data);
                    $('#id').val('');
                    $('#amount').val('0');


                })
                .catch(function(response) {
                    showNotification('bg-danger', 'You have entered invalid data !');
                    //handle error
                    console.log(response);
                });
        }
    }

    function loadData(){
        $('#sellDisDataBody').html("");

        // list
        axios.post('{{ route("admin.distributer.detail.opening.list")}}',{'date': $('#nepali-datepicker').val()})
        .then(function(response) {
            // console.log(response.data);
            $('#sellDisDataBody').html(response.data);
        })
        .catch(function(response) {
            //handle error
            console.log(response);
        });
    }
   

    window.onload = function() {
        $('#id').focus();
        loadData();
    };

    function removeLedger(id){
        $('#ledger-'+id).remove();
    }

    $('#paid').bind('keydown', 'return', function(e){
        saveData();
    });

    $('#id').focusout(function(){
        if($(this).val()!=""){

            if(!exists('#dis-'+$(this).val())){
                alert('Distributor Not Found');
                $(this).focus();
                $(this).select();

            }
        }
    });

    $('#nepali-datepicker').bind('changed', function() {
        loadData();
    });

</script>
@endsection
