@extends('admin.layouts.app')
@section('title', 'Employee Sales')
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('head-title', 'Employee Sales')
@section('toobar')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">

            <form id="form_validation" method="POST" onsubmit="return saveData(event,this);">
                @csrf
                <div class="row">

                    <div class="col-lg-3">
                        <label for="date">Date</label>
                        <input type="text" name="date" id="nepali-datepicker" class="form-control next"
                            data-next="u_id" changed="console.log('event')">
                    </div>

                    <div class="col-lg-3">
                        <label for="u_number">Center</label>
                        <select name="center_id" id="center_id" class="form-control show-tick ms select2">
                            @foreach ($centers as $center)
                                <option value="{{ $center->id }}">
                                    {{ $center->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-5">
                        <label for="u_number">Employee</label>

                        <div class="form-group">
                            <select name="employee_id" id="employee_id" class="form-control show-tick ms select2" required>
                                <option></option>
                                @foreach ($emps as $employee)
                                    <option value="{{ $employee->id }}">
                                        {{ $employee->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="form-group">
                            <!-- <input type="hidden" name=""> -->
                            <label>
                                Item
                            </label>
                            <select name="item_id" id="item_id" class="form-control show-tick ms select2" required>
                                <option ></option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <label for="rate">Rate</label>
                        <input required type="number" name="rate" onkeyup="calTotal(); paidTotal();" id="rate"
                            step="0.001" value="0" placeholder="Item rate" class="form-control  next" data-next="qty"
                            min="0.001">
                    </div>

                    <div class="col-md-2">
                        <label for="qty">Quantity</label>
                        <input required type="number" onfocus="$(this).select();" name="qty" id="qty"
                            onkeyup="calTotal(); paidTotal();" step="0.001" value="1" placeholder="Item quantity"
                            class="form-control  next" data-next="total" min="0.001">
                    </div>

                    <div class="col-md-2">
                        <label for="total">Total</label>
                        <input required type="number" name="total" id="total" step="0.001" placeholder="Total"
                            value="0" class="form-control next connectmax" data-connected="paid" data-next="paid"
                            min="0.001">
                    </div>

                    <div class="col-md-2">
                        <label for="paid">Paid</label>
                        <input required type="number" name="paid" onkeyup="paidTotal();" id="paid" step="0.001"
                            placeholder="Paid" value="0" class="form-control xpay_handle" min="0.001">
                    </div>

                    <div class="col-md-2">
                        <label for="due">Due</label>
                        <input required type="number" name="due" id="due" step="0.001" placeholder="due" value="0"
                            class="form-control" min="0" readonly>
                    </div>
                    <div class="col-lg-2">
                        <input style="margin-top:33px;"  type="submit" id="save" class="btn btn-raised btn-primary waves-effect btn-block"
                            value="Add" >
                    </div>
                    <div class="col-12">
                        <hr>
                        <div class="row">
                            @include('admin.payment.take', ['xpay_type' => 2]);
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
    <hr>
    <div class="pt-2 pb-2">
        <input type="text" id="sid" placeholder="Search">
    </div>
    <div class="table-responsive">
        <table id="newstable1" class="table table-bordered table-striped table-hover js-basic-example dataTable">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Total</th>
                    <th>Paid</th>
                    <th>Due</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="advanceData">

            </tbody>
        </table>
    </div>




@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('calender/nepali.datepicker.v3.2.min.js') }}"></script>
    
    <script>
        const items={!! json_encode($items) !!};
        $("input#nepali-datepicker").bind('click', function(e) {
            // loadEmpSell();
        });


        $("input#nepali-datepicker").bind('changed', function(e) {
            loadEmpSell();
        });


        function calTotal() {
            $('#total').val($('#rate').val() * $('#qty').val());
            $('#due').val($('#rate').val() * $('#qty').val());
            $('#etotal').val($('#erate').val() * $('#eqty').val());
            $('#edue').val($('#erate').val() * $('#eqty').val());
        }
        function paidTotal() {
            var total = parseFloat($('#total').val());
            var paid = parseFloat($('#paid').val());
            var due=total - paid;
            if(due<0){
                due=0;
            }
            $('#due').val(due);

            var etotal = parseFloat($('#etotal').val());
            var epaid = parseFloat($('#epaid').val());
            var edue=etotal - epaid;
            if(edue<0){
                edue=0;
            }
            $('#edue').val(due);
        }

        function del(id) {
            if(prompt("Are you sure to delete employee sales")=='yes'){
                showProgress("Deleting Employee Sales");
                axios.post("{{route('admin.employee.sales.del')}}",{id:id})
                .then((res)=>{
                    hideProgress();
                    showNotification('bg-success','Employee Sales Deleted Sucessfully');
                    $('#sell_item_'+id).remove();
                })
                .catch((err)=>{
                    hideProgress();
                    showNotification('bg-danger',err.response.data.message);
                });
            }
        }
        function loadEmpSell() {
            var date = $('#nepali-datepicker').val();

            axios({
                    method: 'post',
                    url: '{{ route('admin.employee.sales.index') }}',
                    data: {
                        'date': date
                    }
                })
                .then(function(response) {
                    // console.log(response.data);
                    $('#advanceData').empty();
                    $('#advanceData').html(response.data);
                })
                .catch(function(err) {
                    //handle error
                    console.log(err);
                    showNotification('bg-danger', err.response.data);
                });
        }


       


        function saveData(e,ele) {
            e.preventDefault();
            if ($('#title').val() == "" || $('#amount').val() == 0) {
                alert('Please enter empty field!');
                $('#title').focus();
                return false;
            } else {
                if (!xpayVerifyData()) {
                    return;
                }

                var bodyFormData = new FormData(ele);
                axios({
                        method: 'post',
                        url: '{{ route('admin.employee.sales.save') }}',
                        data: bodyFormData,
                    })
                    .then(function(response) {
                        console.log(response);
                        // showNotification('bg-success', 'Employee advance added successfully!');
                        // $('#largeModal').modal('toggle');
                        $('#advanceData').prepend(response.data);
                        // ele.reset();
                        $('#employee_id').val(null).trigger('change');
                        $('#item_id').val(null).trigger('change');
                        $('#rate').val(0).trigger('change');
                        $('#employee_id').select2('focus');

                    })
                    .catch(function(err) {
                        //handle error
                        console.log(err);
                        if(err.response){

                            showNotification('bg-danger', err.response.data.message);
                        }else{
                            showNotification('bg-danger', "Some error occured please try again");
                            
                        }
                    });

            }
        }


        window.onload = function() {
            var mainInput = document.getElementById("nepali-datepicker");
            mainInput.nepaliDatePicker();
            var month = ('0' + NepaliFunctions.GetCurrentBsDate().month).slice(-2);
            var day = ('0' + NepaliFunctions.GetCurrentBsDate().day).slice(-2);
            $('#nepali-datepicker').val(NepaliFunctions.GetCurrentBsYear() + '-' + month + '-' + day);
            $('.select2').select2({allowClear: true});
            loadEmpSell();
            $('#item_id').change(function (e) { 
                e.preventDefault();
                // alert(this.value);
                $('#rate').val(null).trigger('change');
                const item=items.find(o=>o.id==this.value);
                if(item!=undefined){
                    $('#rate').val(item.sell_price).trigger('change');
                    $('#rate').focus();
                    calTotal();
                    paidTotal();
                }
            });

            $('#employee_id').select2('focus');



        };
    </script>
@endsection
