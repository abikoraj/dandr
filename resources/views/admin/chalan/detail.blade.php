@extends('admin.layouts.app')
@section('title','Employee Chalan Details')
@section('head-title')
    <a href="{{ route('admin.chalan.index') }}">Employee Chalan </a> /
    Chalan Details
@endsection
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

    <div class="col-md-12 bg-light pt-2">
        <div>
                <input type="hidden" id="curdate">
                    <form action="" id="chalanItems">
                        @csrf

                        <div class="row" id="bill">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="date">Date</label>
                                    <input readonly type="text" name="date" id="nepali-datepicker"
                                        class="calender form-control next" data-next="user_id" placeholder="Date">
                                </div>
                            </div>

                            <input type="hidden" name="employee_chalan_id" value="{{$datas->id}}">

                            <div class="col-md-4">
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


                            <div class="col-md-4">
                                <div class="form-group">
                                    <p><strong> Select Items </strong></p>
                                    <div class="mb-1">
                                        <select id="item_id" name="item_id" class="form-control show-tick select ms" data-live-search="true">
                                            <option></option>
                                            @foreach ($items as $item)
                                               <option value="{{ $item->item_id }}" data-itemrate="{{$item->rate}}" data-itemtitle="{{ $item->title }}">{{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                  <input type="hidden" id="item_name" name="item_name"  value="">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label for="rate">Rate</label>
                                <input type="number" name="rate" onkeyup="calTotal();" id="rate"
                                    step="0.001" value="0" placeholder="Item rate" class="form-control  next" data-next="qty"
                                    min="0.001">
                            </div>

                            <div class="col-md-3">
                                <label for="qty">Quantity</label>
                                <input type="number" onfocus="$(this).select();" name="qty" id="qty"
                                    onkeyup="calTotal();" step="0.001" value="1" placeholder="Item quantity"
                                    class="form-control  next" data-next="total" min="0.001">
                            </div>
                            <input type="hidden" name="">

                            <div class="col-md-3">
                                <label for="total">Total</label>
                                <input type="number" name="total" id="total" step="0.001" placeholder="Total"
                                    value="0" class="form-control next connectmax" data-connected="paid" data-next="paid"
                                    min="0.001">
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

        function calTotal(){
            var rate = parseFloat($('#rate').val());
            var qty = parseFloat($('#qty').val());
            $('#total').val(rate*qty);
        }

        window.onload = function () {
            var date = $('#nepali-datepicker').val();
            axios.post('{{ route('admin.chalan.chalan.list')}}',{date:date})
                .then(function(response) {
                    // console.log(response.data);
                    $('#sellDataBody').prepend(response.data);
                })
                .catch(function(response) {
                    //handle error
                    console.log(response);
                });
        }

        function deleteSell(id){
            axios.post('{{ route('admin.chalan.chalan.delete')}}',{id:id})
                .then(function(response) {
                    // console.log(response.data);
                    showNotification('bg-success', 'Sellitem Delete successfully !');
                    $('#sell-'+id).remove();
                    calculateTotal();
                })
                .catch(function(response) {
                    //handle error
                    console.log(response);
                });
        }

        function calculateTotal(){
            let total=0;
            document.querySelectorAll('.sell-total')
            .forEach((ele)=>{
                total+=parseFloat(ele.dataset.total);
            });
            $('#tot').html(total);
                }

</script>

@endsection
