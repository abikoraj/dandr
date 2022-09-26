@extends('admin.layouts.app')
@section('title', 'Employee Chalan Details')
@section('head-title')
    <a href="{{ route('admin.chalan.index') }}">Employee Chalan </a> /
    Chalan Details
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
    <style>
        td,
        th {
            border: 1px solid black !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-header-group;
        }

        .step-btn {
            cursor: pointer;
            padding: 10px 15px;
            flex: 1;
            text-align: center;
        }



        .step-btn.active {
            background: rgb(0, 122, 204);
            color: white;
        }

        .step-div {
            display: none;

        }

        .step-div.active {
            display: block;
        }

    </style>
@endsection
@section('head-title', 'Distributer Sell')
@section('toobar')

@endsection
@section('content')


    <div class="d-flex shadow-local">
        <div class="steps step-btn  step-1 active" onclick="CurrentStep=1;refresh();">
            Chalan Sell
        </div>
        <div class="steps step-btn  step-2" onclick="CurrentStep=2;refresh();">
            Chalan Payments
        </div>
        <div class="steps step-btn  step-3" onclick="CurrentStep=3;refresh();">
            Wastage Items
        </div>
    </div>
    <div class="p-2 mt-3  shadow-local">

        <div class="steps step-div step-1 active">

            <div class="col-md-12 bg-light pt-2">
                <div>
                    <input type="hidden" id="curdate">
                    <form action="" id="chalanItems">
                        @csrf

                        <div class="row" id="bill">
                            {{-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="date">Date</label>
                                                <input readonly type="text" name="date" id="nepali-datepicker"
                                                    class="calender form-control next" data-next="user_id" placeholder="Date">
                                            </div>
                                        </div> --}}

                            <input type="hidden" id="employee_chalan_id" name="employee_chalan_id"
                                value="{{ $datas->id }}">
                            <input type="hidden" name="date" id="nepali-datepicker" value="{{ _nepalidate($datas->date) }}">
                            <div class="col-md-6">
                                <p><strong> Select Customer </strong></p>
                                <div class="mb-1">
                                    <select name="user_id" class="form-control show-tick select ms" data-live-search="true">
                                        <option></option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <p><strong> Select Items </strong></p>
                                    <div class="mb-1">
                                        <select id="item_id" name="item_id" class="form-control show-tick select ms"
                                            data-live-search="true">
                                            <option></option>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->item_id }}" data-itemrate="{{ $item->rate }}"
                                                    data-itemtitle="{{ $item->title }}">{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" id="item_name" name="item_name" value="">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label for="rate">Rate</label>
                                <input type="number" name="rate" onkeyup="calTotal();" id="rate" step="0.001" value="0"
                                    placeholder="Item rate" class="form-control  next" data-next="qty" min="0.001">
                            </div>

                            <div class="col-md-3">
                                <label for="qty">Quantity</label>
                                <input type="number" onfocus="$(this).select();" name="qty" id="qty" onkeyup="calTotal();"
                                    step="0.001" value="1" placeholder="Item quantity" class="form-control  next"
                                    data-next="total" min="0.001">
                            </div>
                            <input type="hidden" name="">

                            <div class="col-md-3">
                                <label for="total">Total</label>
                                <input type="number" name="total" id="total" step="0.001" placeholder="Total" value="0"
                                    class="form-control next connectmax" data-connected="paid" data-next="paid" min="0.001">
                            </div>


                            <div class="col-md-4 mt-4">
                                <input type="button" value="Save" class="btn btn-primary btn-block" onclick="saveData();"
                                    id="save">
                                {{-- <span class="btn btn-primary btn-block" >Save</span> --}}
                            </div>

                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mt-5">
                                <div class="pt-2 pb-2">
                                    <input type="text" id="sellItemId" placeholder="Search" style="width: 200px;">
                                </div>
                                <table id="newstable1" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Item Name</th>
                                            <th>Rate</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="sellDataBody">

                                    </tbody>
                                </table>


                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="steps step-div step-2">
            <div class="col-md-12 bg-light pt-2">
                <div>
                    <input type="hidden" id="curdate">
                    <form action="" id="chalanItems">
                        @csrf

                        <div class="row" id="bill">


                            <input type="hidden" id="employee_chalan_id" name="employee_chalan_id"
                                value="{{ $datas->id }}">
                            <input type="hidden" name="date" id="nepali-datepicker"
                                value="{{ _nepalidate($datas->date) }}">
                            <div class="col-md-5">
                                <p><strong> Select Customer </strong></p>
                                <div class="mb-1">
                                    <select name="user_id" class="form-control show-tick select ms" data-live-search="true">
                                        <option></option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" id="amount" step="0.001" placeholder="Amount" value="0"
                                    class="form-control next connectmax" min="0.001">
                            </div>


                            <div class="col-md-4 mt-4">
                                <input type="button" value="Save" class="btn btn-primary btn-block"
                                    onclick="chalanPayment();" id="save">
                                {{-- <span class="btn btn-primary btn-block" >Save</span> --}}
                            </div>

                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mt-5">
                                <div class="pt-2 pb-2">
                                    <input type="text" id="sellItemId" placeholder="Search" style="width: 200px;">
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="chalan-payments">

                                    </tbody>
                                </table>


                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="steps step-div step-3">

        </div>
    </div>

    <!-- edit modal -->

@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('backend/js/pages/forms/advanced-form-elements.js') }}"></script>

    <script>
        // $( "#x" ).prop( "disabled", true );
        $('.select').select2();



        function saveData() {
            if ($('#nepali-datepicker').val() == '' || $('#item_id').val() == '' || $('#total')
                .val() == 0) {
                alert('Please enter data in empty field !');
                $('#nepali-datepicker').focus();
                return false;
            } else {



                var bodyFormData = new FormData(document.getElementById('chalanItems'));
                axios({
                        method: 'post',
                        url: '{{ route('admin.chalan.chalan.save') }}',
                        data: bodyFormData,
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(function(response) {
                        console.log(response.data);
                        showNotification('bg-success', 'Sellitem added successfully !');
                        $('#sellDataBody').prepend(response.data);
                        $('#item_id').val('');
                        $('#rate').val('');
                        $('#qty').val(1);
                        $('#total').val(0);
                        calculateTotal();
                    })
                    .catch(function(error) {
                        showNotification('bg-danger', error.response.data);
                        //handle error
                        console.log(error.response);
                        lock = false;
                    });
            }
        }

        $('#item_id').on('change', function() {
            var item_id = $(this).find(":selected").val();
            var rate = $(this).find(':selected').data("itemrate");
            $('#rate').val(rate);

            var title = $(this).find(':selected').text();
            $('#item_name').val(title);

            calTotal();
        });

        function calTotal() {
            var rate = parseFloat($('#rate').val());
            var qty = parseFloat($('#qty').val());
            $('#total').val(rate * qty);
        }

        window.onload = function() {
            var employee_chalan_id = $('#employee_chalan_id').val();
            axios.post('{{ route('admin.chalan.chalan.list') }}', {
                    employee_chalan_id: employee_chalan_id
                })
                .then(function(response) {
                    // console.log(response.data);
                    $('#sellDataBody').prepend(response.data);
                })
                .catch(function(response) {
                    //handle error
                    console.log(response);
                });
            axios.post('{{ route('admin.chalan.payment.index') }}', {
                    employee_chalan_id: employee_chalan_id
                })
                .then(function(response) {
                    // console.log(response.data);
                    $('#chalan-payments').prepend(response.data);
                })
                .catch(function(response) {
                    //handle error
                    console.log(response);
                });
        }

        function deleteSell(id) {
            if (prompt('Enter yes to continue') == 'yes') {

                showProgress('Deleting sell Item');
                axios.post('{{ route('admin.chalan.chalan.delete') }}', {
                        id: id
                    })
                    .then(function(response) {
                        // console.log(response.data);
                        showNotification('bg-success', 'Sellitem deleted successfully !');
                        $('#sell-' + id).remove();
                        calculateTotal();
                        hideProgress();


                    })
                    .catch(function(err) {
                        hideProgress();
                        const msg = err.response ? err.response.data.message : 'Please try again';
                        showNotification('bg-danger', 'Sellitem not deleted,' + msg);

                    });
            }
        }

        function delPayment(id) {
            if (prompt('Enter yes to continue') == 'yes') {

                showProgress('Deleting  payment');
                axios.post('{{ route('admin.chalan.payment.del') }}', {
                        id: id
                    })
                    .then(function(response) {
                        // console.log(response.data);
                        showNotification('bg-success', 'Payment deleted successfully !');
                        $('#payment-' + id).remove();
                        calculateTotal();
                        hideProgress();


                    })
                    .catch(function(err) {
                        hideProgress();
                        const msg = err.response ? err.response.data.message : 'Please try again';
                        showNotification('bg-danger', 'Payment not deleted,' + msg);

                    });
            }
        }

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.sell-total')
                .forEach((ele) => {
                    total += parseFloat(ele.dataset.total);
                });
            $('#tot').html(total);
        }


        function refresh() {
            $('.steps').removeClass('active');
            $('.step-' + CurrentStep).addClass('active');
            $('#add-type').html(steps[CurrentStep]);
            if (CurrentStep == 1 || CurrentStep == 3) {
                $('#current-stock-holder').show();
                $('#batch_id_holder').show();
            } else {
                $('#current-stock-holder').hide();
                $('#batch_id_holder').hide();
            }
        }



        function chalanPayment() {
            if ($('#user_id').val() == "" || $('#amount').val() == 0) {
                alert('Please enter data in empty field !');
                $('#user_id').focus();
                return false;
            } else {

                var bodyFormData = new FormData(document.getElementById('chalanItems'));
                axios({
                        method: 'post',
                        url: '{{ route('admin.chalan.chalan.save') }}',
                        data: bodyFormData,
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(function(response) {
                        console.log(response.data);
                        showNotification('bg-success', 'Sellitem added successfully !');
                        $('#sellDataBody').prepend(response.data);
                        $('#item_id').val('');
                        $('#rate').val('');
                        $('#qty').val(1);
                        $('#total').val(0);
                        calculateTotal();
                    })
                    .catch(function(error) {
                        showNotification('bg-danger', error.response.data);
                        //handle error
                        console.log(error.response);
                        lock = false;
                    });
            }
        }

    </script>

@endsection
