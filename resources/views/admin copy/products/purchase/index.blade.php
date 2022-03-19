@extends('admin.layouts.app')
@section('title','Product Purchase')
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('head-title','Product Purchase')
@section('toobar')
@endsection
@section('content')
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
<div class="row">
    <div class="col-md-12 bg-light">
        <form action="{{ route('purchase.store') }}" method="POST" id="milkData">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="text" name="date" id="nepali-datepicker" class="form-control next" data-next="center_id" placeholder="Date">
                    </div>
                </div>

                <input type="hidden" name="counter" id="counter" val=""/>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="reqireqty">Bill No.</label>
                        <input type="number"  step="0.01" name="billno" placeholder="Bill No." class="form-control" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="product">Purchase Items</label>
                        <select id="product" class="form-control show-tick ms select2" data-placeholder="Select">
                            <option value="-1"></option>
                            @foreach(\App\Models\Product::all() as $p)
                              <option  value='{{$p->toJson()}}'>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="from-group">
                        <label for="qty"> Quantity (Ltr./Kg.) </label>
                        <input type="number" onkeyup="singleItemTotal();" class="form-control" id="qty" value="1" min="0" step="0.001">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="from-group">
                        <label for="rate"> Rate </label>
                        <input type="number" onkeyup="singleItemTotal();" class="form-control" id="rate" value="0" min="0" step="0.001">
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="from-group">
                        <label for="rate"> Total </label>
                        <input type="number" class="form-control" id="total" value="0" min="0" step="0.001">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <span class="btn btn-primary btn-block" style="margin-top:2rem;" onclick="addItems();">Add</span>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5">
                        <table id="newstable1" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Required Item</th>
                                    <th>Qty</th>
                                    <th>Rate</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="itemBody">

                            </tbody>
                        </table>
                        <div class="text-right">
                            Grand Total : <input type="number" name="gtotal" readonly id="itotal" value="0"></td>

                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <button class="btn btn-primary btn-block">Save Items</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('backend/js/pages/forms/advanced-form-elements.js') }}"></script>
    <script src="{{ asset('calender/nepali.datepicker.v3.2.min.js') }}"></script>
    <script>
        var month = ('0'+ NepaliFunctions.GetCurrentBsDate().month).slice(-2);
        var day = ('0' + NepaliFunctions.GetCurrentBsDate().day).slice(-2);
        $('#nepali-datepicker').val(NepaliFunctions.GetCurrentBsYear() + '-' + month + '-' + day);

    window.onload = function() {
        var mainInput = document.getElementById("nepali-datepicker");
        mainInput.nepaliDatePicker();
    };

    function singleItemTotal() {
        $('#total').val($('#rate').val() * $('#qty').val());
    }

    var i = 0;
    var itemKeys = [];

    function addItems() {
        if ($('#product').val() == -1 || $('#qty').val() == 0 || $('#rate').val() == 0) {
            alert('Please fill the above related field');
            $("#product").focus();
            return false;
        }
        product=JSON.parse($('#product').val());
        // console.log(product);
        html = "<tr id='row-" + i + "'>";
        html += "<td>" + product.name + "<input type='hidden' name='productid_" + i + "' value='" + product.id + "' /><input type='hidden' name='productname_" + i + "' value='" + product.name + "' /></td>";
        html += "<td>" + $('#qty').val() + "<input type='hidden' name='qty_" + i + "' value='" + $('#qty').val() + "'/></td>";
        html += "<td>" + $('#rate').val() + "<input type='hidden' name='rate_" + i + "' value='" + $('#rate').val() + "'/></td>";
        html += "<td>" + $('#total').val() + "<input type='hidden' name='total_" + i + "' id='total_" + i + "'  value='" + $('#total').val() + "'/></td>";
        html += "<td> <span class='btn btn-danger btn-sm' onclick='RemoveItem(" + i + ");'>Remove</span></td>";
        html += "</tr>";
        $("#itemBody").append(html);
        $('#product').val(-1).change();
        $('#rate').val('0');
        $('#qty').val('0');
        $('#product').focus();
        $('#total').val(0);
        itemKeys.push(i);
        i+= 1;
        suffle();
    }

    function suffle(){
        $("#counter").val(itemKeys.join(","));
        calculateTotal();
    }

    function RemoveItem(e){
        $('#row-'+e).remove();
        var index=$.inArray(e,itemKeys);
            if(index>-1){
                itemKeys.splice(index,1);
            }
            suffle();
    }

    function calculateTotal() {
        var itotal = 0;
        for (let index = 0; index < itemKeys.length; index++) {
            const element = itemKeys[index];
            itotal += parseInt($("#total_" + element).val());;
        }
        $('#itotal').val(itotal);
    }






    </script>
@endsection
