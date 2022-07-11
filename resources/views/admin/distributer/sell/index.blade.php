@extends('admin.layouts.app')
@section('title','Distributer Sell')
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
@section('head-title','Distributer Sell')
@section('toobar')

@endsection
@section('content')
<div class="row">
    @include('admin.distributer.sell.productmodal')
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
                        <input readonly type="text" name="date" id="nepali-datepicker" class="calender form-control next" data-next="user_id" placeholder="Date">
                    </div>
                </div>


                <div class="col-md-2">
                    <div class="form-group">
                        <label for="id">Distributer</label>
                        <input type="number" id="id" name="id" class="form-control next" data-next="product_id">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="product_id"><span id="productsearch"  data-toggle="modal" data-target="#itemmodal">{{env('use_barcode',false)?"Barcode":"Product"}} ( search )</span></label>
                        <input type="{{env('use_barcode',false)?"text":"number"}}" id="product_id" name="product_id"  class="form-control next" data-next="rate">
                    </div>
                </div>

                <div class="col-md-2">
                    <label for="rate">Rate</label>
                    <input type="number" name="rate" oninput="calTotal(); paidTotal();" id="rate" step="0.001" value="0" placeholder="Item rate" class="form-control  next focus-select" data-next="qty" min="0.001">
                </div>

                <div class="col-md-2">
                    <label for="qty">Quantity</label>
                    <input type="number" onfocus="$(this).select();" name="qty" id="qty" oninput="calTotal(); paidTotal();" step="0.001" value="1" placeholder="Item quantity" class="form-control  next" data-next="paid" min="0.001">
                </div>

                <div class="col-md-3">
                    <label for="total">Total</label>
                    <input type="number" name="total" id="total" step="0.001" placeholder="Total" value="0" class="form-control next connectmax" data-connected="paid" data-next="paid" min="0.001" readonly>
                </div>

                <div class="col-md-3">
                    <label for="paid">Paid</label>
                    <input type="number" name="paid" oninput="paidTotal();" id="paid" step="0.001" placeholder="Paid" value="0" class="form-control focus-select xpay_handle" min="0.001">
                </div>

                <div class="col-md-3">
                    <label for="due">Due</label>
                    <input type="number" name="due" id="due" step="0.001" placeholder="due" value="0" class="form-control" min="0" readonly>
                </div>

                <div class="col-md-3 mt-4">
                    <input type="button" value="Save" class="btn btn-primary btn-block" onclick="saveData();" id="save">
                    {{-- <span class="btn btn-primary btn-block" >Save</span> --}}
                </div>
                <div class="col-12 pt-1">
                    <div class="row">
                        @include('admin.payment.take')
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
                                <th>Distributer</th>
                                <th>Product</th>
                                <th>Rate</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
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

@include('admin.distributer.sell.editmodal')
@endsection
@section('js')
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/pages/forms/advanced-form-elements.js') }}"></script>
    @if($large)
        @include('admin.search.item')
    @endif
<script>
    // $( "#x" ).prop( "disabled", true );
    initTableSearch('sId', 'sellDisDataBody', ['name']);
    initTableSearch('s_dis', 'distributors', ['name']);
    initTableSearch('isid', 'itemData', ['number','name']);


    //XXX Select Item From Search
    function itemSelected(data) {

        @if($large)
            $('#product_id').closeSearch();
        @endif
       $('#product_id').val(data.number);
       $('#rate').val(data.rate);
       $('#itemmodal').modal('hide');
       $('#rate').focus();
       $('#rate').select();
       calTotal();

   }
    sellock=false;
    function saveData() {
        if(!sellock){

        if ($('#nepali-datepicker').val() == '' || $('#id').val() == ''||$('#product_id').val() == '' || $('#total').val() == 0) {
            alert('Please enter data in empty field !');
            $('#id').focus();
            return false;
        } else {
            if(!xpayVerifyData()){
                return;
            }
            console.log("sell_"+$('#id').val());
            if(exists(".sell_"+$('#id').val())){
                if(!confirm("Sell Data Already added, Do You Want Add Another?")){
                    return;
                }
            }
            sellock=true;
            var bodyFormData = new FormData(document.getElementById('sellitemData'));
            axios({
                    method: 'post',
                    url: '{{ route("admin.distributer.sell.add")}}',
                    data: bodyFormData,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function(response) {
                    console.log(response.data);
                    // showNotification('bg-success', 'Sellitem added successfully !');
                    $('#sellDisDataBody').prepend(response.data);
                    $('#id').val('');
                    $('#product_id').val('');
                    $('#rate').val('');
                    $('#qty').val(1);
                    $('#total').val(0);
                    $('#paid').val(0);
                    $('#due').val(0);
                    $('#xpay_amount').val(0);
                    $('#id').focus();
                    showNotification('bg-success', 'Sell item added successfully !');
                    sellock=false;
                })
                .catch(function(response) {
                    sellock=false;

                    showNotification('bg-danger', 'You have entered invalid data !');
                    //handle error
                    console.log(response);
                });
        }
        }
    }

    // delete

    function removeData(id) {
        if (confirm('Are you sure?')) {
            if(prompt("Enter Yes To Delete","no").toLocaleLowerCase()==="yes"){

                axios({
                        method: 'post',
                        url: '{{route("admin.distributer.sell.del")}}',
                        data:{
                            'id':id,
                            'date':$('#currentdate').val()
                        }
                    })
                    .then(function(response) {
                        showNotification('bg-danger', 'Sellitem deleted successfully!');
                        $('#sell-' + id).remove();
                    })
                    .catch(function(response) {
                        console.log(response)
                        showNotification('bg-danger', 'You hove no authority!');

                    })
            }
        }
    }


    function calTotal() {
        $('#total').val($('#rate').val() * $('#qty').val());
        // $('#due').val($('#rate').val() * $('#qty').val());
        var total = parseFloat($('#total').val());
        var paid = parseFloat($('#paid').val());
        due=total-paid;
        if(due<0){
            due=0;
        }
        $('#due').val(due);
        $('#etotal').val($('#erate').val() * $('#eqty').val());
        $('#edue').val($('#erate').val() * $('#eqty').val());
    }

    function paidTotal() {
        var total = parseFloat($('#total').val());
        var paid = parseFloat($('#paid').val());
        due=total-paid;
        if(due<0){
            due=0;
        }
        $('#due').val(due);
        var etotal = parseFloat($('#etotal').val());
        var epaid = parseFloat($('#epaid').val());
        $('#edue').val(etotal - epaid);
    }


    $('#u_id').change(function() {
     var id = $(this).val();
     _rate = document.querySelector('#opt-'+id).dataset.rate;
     $('#rate').val(_rate);
     _qty = document.querySelector('#opt-'+id).dataset.qty;
     $('#qty').val(_qty);
     calTotal();
     $('#rate').focus();
     $('#rate').select();
   });

    function loaddata(){
        $('#sellDisDataBody').html("");

        // list
        axios.post('{{ route("admin.distributer.sell.list")}}',{'date': $('#nepali-datepicker').val()})
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
        loaddata();
    };


    $('#paid').bind('keydown', 'return', function(e){
        saveData();
    });

    $('#id').focusout(function(){
        if($(this).val()!=""){

            if(!exists('#dis-'+$(this).val())){
                alert('Distributor Not Found');
                $(this).focus();
                $(this).select();
                $(this).val("");
            }
        }
    });
    @if(!$large)
        $('#product_id').focusout(function(){
            if($(this).val()!=""){
                if(!exists('#item-'+$(this).val())){
                    alert('Product Not Found');
                    $(this).focus();
                    $(this).select();
                    $(this).val("");
                }
                $('#rate').val($('#item-'+$(this).val()).data('rate')).change();
                calTotal();
            }
        });
    @endif

    $('#nepali-datepicker').bind('changed', function() {
        loaddata();
    });

</script>
@if ($large)

    <script>
        function renderBarcode(){
            html="<table>";
                this.data.forEach($i => {
                    html+= '<tr class="search-item" id="item-'+ ($i.dis_number??$i.number) +'" data-rate="'+($i.dis_price??$i.sell_price)+'" data-number="'+($i.dis_number??$i.number) +'" data-name="'+ $i.title +'" onclick="itemSelected(this.dataset);">'+
                                '<td class="p-1"><span style="cursor: pointer;">'+ ($i.dis_number??$i.number) +'</span></td>'+
                            '</tr>';
                });
                html+="</table>"
                return html;
        }

        $('#product_id').search({
            url:'{{route('admin.item.product-barcode')}}',
            renderfunc:"renderBarcode",
            mod:"bar"
        });
    </script>
@endif
@endsection
