@extends('admin.layouts.app')
@section('title','Manufacture Items')
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('head-title','Manufacture Items')
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
        <form action="{{ route('manufacture.store') }}" method="POST" id="milkData">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="text" name="date" id="nepali-datepicker" class="form-control next" data-next="center_id" placeholder="Date">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="item">Manufacture Items</label>
                        <select name="item_id" class="form-control show-tick ms next" data-next="session" required>
                            <option></option>
                            @foreach(\App\Models\Product::all() as $p)
                            <option value="{{$p->id}}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <input type="hidden" name="counter" id="counter" val=""/>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="reqireqty">Manufactured Qty(Kg./Ltr.)</label>
                        <input type="number" value="1" min="0.01" step="0.01" name="m_qty" class="form-control">
                    </div>
                </div>

                <div class="col-12" style="border: 1px #ccc solid; margin:2rem 0;"></div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="product">Required Items</label>
                        <select id="product" class="form-control show-tick ms next" data-next="session">
                            <option></option>
                            @foreach(\App\Models\Product::all() as $p)
                              <option  value='{{$p->toJson()}}'>{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date">Available Stock(Kg./Ltr.)</label>
                        <input type="number" id="available" class="form-control next" readonly>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date">Required Unit(Kg./Ltr.)</label>
                        <input type="number" min="0.01" step="0.01" id="reqQty" oninput="checkStock();" class="form-control next" value="0.01" placeholder="qty">
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
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="itemBody">

                            </tbody>
                        </table>
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

    var i = 0;
    var itemKeys = [];

    function addItems() {
        if ($('#product').val() == "" || $('#reqQty').val() == 0) {
            alert('Please fill the above related field');
            $("#product").focus();
            return false;
        }
        product=JSON.parse($('#product').val());
        // console.log(product);
        html = "<tr id='row-" + i + "'>";
        html += "<td>" + product.name + "<input type='hidden' name='productid_" + i + "' value='" + product.id + "' /></td>";
        html += "<td>" + $('#reqQty').val() + "<input type='hidden' name='reqQty_" + i + "' value='" + $('#reqQty').val() + "'/></td>";
        html += "<td> <span class='btn btn-danger btn-sm' onclick='RemoveItem(" + i + ");'>Remove</span></td>";
        html += "</tr>";
        $("#itemBody").append(html);
        $('#product').val('');
        $('#reqQty').val('0.01');
        itemKeys.push(i);
        i+= 1;
        suffle();
    }

    function suffle(){
        $("#counter").val(itemKeys.join(","));
    }

    function RemoveItem(e){
        $('#row-'+e).remove();
        var index=$.inArray(e,itemKeys);
            if(index>-1){
                itemKeys.splice(index,1);
            }
            suffle();
    }


    $('#product').on('change', function() {
        product=JSON.parse($('#product').val());
        $('#available').val(product.stock);
    });

    function checkStock(){
        var req = parseFloat($('#reqQty').val());
        var stock =parseFloat($('#available').val());
        if(req>stock){
            alert('Stock is not sufficient !');
            $('#reqQty').val(0);
            $('#reqQty').select();
        }
    }

    </script>
@endsection
