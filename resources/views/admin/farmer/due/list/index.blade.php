@extends('admin.layouts.app')
@section('title','Farmer Balance')
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
@section('head-title','Farmer Balance')
@section('toobar')

@endsection
@section('content')
<div class="row">
    {{-- @include('admin.distributer.sell.productmodal') --}}
    <div class="col-md-3">
        <div id="_farmers">

        </div>
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date">Collection Center</label>
                        <select name="center_id" id="center_id" class="form-control show-tick ms next">
                            <option></option>
                            @foreach(\App\Models\Center::all() as $c)
                            <option value="{{$c->id}}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 pt-4">
                    <span id="loaddata" class="btn btn-success" onclick="loadData()">Load</span>
                    <span id="resetdata" class="btn btn-danger d-none " onclick="resetData()">reset</span>
                </div>
            </div>
            <div id="alldata" class="d-none">

                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id">Farmer</label>
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
                        <tbody id="datas">

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
<script>
    // $( "#x" ).prop( "disabled", true );
    initTableSearch('sId', 'datas', ['name']);
    initTableSearch('s_dis', 'distributors', ['name']);
    initTableSearch('productsearch', 'products', ['name']);


    function farmerSelected(id){
        $('#id').focus();
        $('#id').val();
    }
    function saveData() {
        if ($('#nepali-datepicker').val() == '' || $('#id').val() == '' || $('#amount').val()=="" || $('#amount').val()=="0") {
            alert('Please enter data in empty field !');
            $('#id').focus();
            return false;
        } else {
            var bodyFormData = new FormData(document.getElementById('sellitemData'));
            axios({
                    method: 'post',
                    url: '{{ route("admin.farmer.due.add")}}',
                    data: bodyFormData,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function(response) {
                    console.log(response.data);
                    showNotification('bg-success', 'Balance added successfully !');
                    $('#datas').prepend(response.data);
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

   
    //delete
    function removeData(id) {
        $('#ledger-' + id).remove();
    }

    function resetData(){
            $('#resetdata').addClass('d-none');
            $('#loaddata').removeClass('d-none');
            $('#datas').html("");
            $('#alldata').addClass('d-none')
    }

    function loadData(){
        $('#datas').html("");
        // list
        axios.post('{{ route("admin.farmer.due.add.list")}}',{
            'date': $('#nepali-datepicker').val(),
            'center':$('#center_id').val()
            })
        .then(function(response) {
            // console.log(response.data);
            $('#datas').html(response.data);
            $('#resetdata').removeClass('d-none');
            $('#loaddata').addClass('d-none');
            $('#alldata').removeClass('d-none')
        })
        .catch(function(response) {
            //handle error
            console.log(response);
        });
    }
  
    window.onload = function() {
       
        $('#id').focus();
    };




    $('#id').focusout(function(){
        if($(this).val()!=""){

            if(!exists('#farmer-'+$(this).val())){
                alert('Distributor Not Found');
                $(this).focus();
                $(this).select();

            }
        }
    });

    $('#nepali-datepicker').bind('changed', function() {
        loaddata();
    });

    $('#center_id').change(function(){
        var center_id = $('#center_id').val();
        axios({
            method: 'post',
            url: '{{ route("admin.farmer.minlist-bycenter")}}',
            data:{'center':center_id}
        })
        .then(function(response) {
            $('#_farmers').html(response.data);
            initTableSearch('sid', 'farmerData', ['name']);
        })
        .catch(function(response) {
            //handle error
            console.log(response);
        });
    })

   

</script>
@endsection
