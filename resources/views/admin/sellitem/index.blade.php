@extends('admin.layouts.app')
@section('title','Sell Items')
@section('css')
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('head-title','Sell Items')
@section('toobar')

@endsection
@section('content')
    @include('admin.item.itemmodal')
<div class="row">
    <div class="col-md-3">
        <div id="_farmers">
            Select Collection center for load farmers !
        </div>
    </div>

    <input type="hidden" id="curdate">
    <div class="col-md-9 bg-light pt-2">
        <form action="" id="sellitemData">
            @csrf
            <div class="row">
                <div class="col-md-12">
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
            </div>
            <div class="row d-none" id="bill">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input readonly type="text" name="date" id="nepali-datepicker" class="calender form-control next" data-next="user_id" placeholder="Date">
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label for="unumber">User Number</label>
                        <input type="number" name="user_id" id="u_id" placeholder="User number" class="form-control checkfarmer next  " data-next="item_id" min="1">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <!-- <input type="hidden" name=""> -->
                        <label for="unumber">Item Number
                            <span id="itemsearch"  data-toggle="modal" data-target="#itemmodal">( search (alt+s) )</span>
                        </label>
                        <input type="text" id="item_id" name="number" placeholder="Item number" class="form-control {{!$large?'checkitem':''}} next " data-rate="rate" data-next="rate" min="1" >
                    </div>
                </div>

                <div class="col-md-2">
                    <label for="rate">Rate</label>
                    <input type="number" name="rate" onkeyup="calTotal(); paidTotal();" id="rate" step="0.001" value="0" placeholder="Item rate" class="form-control  next" data-next="qty" min="0.001">
                </div>

                <div class="col-md-3">
                    <label for="qty">Quantity</label>
                    <input type="number" onfocus="$(this).select();" name="qty" id="qty" onkeyup="calTotal(); paidTotal();" step="0.001" value="1" placeholder="Item quantity" class="form-control  next" data-next="paid" min="0.001">
                </div>

                <div class="col-md-3">
                    <label for="total">Total</label>
                    <input type="number" name="total" id="total" step="0.001" placeholder="Total" value="0" class="form-control next connectmax" data-connected="paid" data-next="paid" min="0.001" readonly>
                </div>

                <div class="col-md-3">
                    <label for="paid">Paid</label>
                    <input type="number" name="paid" onkeyup="paidTotal();" id="paid" step="0.001" placeholder="Paid" value="0" class="form-control" min="0.001">
                </div>

                <div class="col-md-3">
                    <label for="due">Due</label>
                    <input type="number" name="due" id="due" step="0.001" placeholder="due" value="0" class="form-control" min="0" readonly>
                </div>

                <div class="col-md-12 d-flex justify-content-end mt-3">
                    <input type="button" value="Save" class="btn btn-primary btn-block" onclick="saveData();" id="save">
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
                                <th><input type="checkbox" id="allchecked" onchange="selectAll(this.checked);"> Farmer No.</th>
                                <th>Item Name</th>
                                <th>Rate</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="sellDataBody">

                        </tbody>
                    </table>

                    <div class="pt-2">
                        <button class="btn btn-danger" onclick="deleteAll()">Delete Selected</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

<!-- edit modal -->

@include('admin.sellitem.editmodal')
@endsection
@section('js')
@if($large)
    @include('admin.search.item')
@endif

<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/pages/forms/advanced-form-elements.js') }}"></script>
<script>
    // $( "#x" ).prop( "disabled", true );
    initTableSearch('sid', 'farmerData', ['name']);
    @if(!$large)
        initTableSearch('isid', 'itemData', ['number','name']);
    @endif
    initTableSearch('sellItemId', 'sellDataBody', ['id', 'item_number']);

    function initEdit(e) {
        var itemsell = JSON.parse(e.dataset.itemsell);
        console.log(itemsell);
        $('#enepali-datepicker').val(itemsell.date);
        $('#eu_id').val(itemsell.user.no);
        $('#eitem_id').val(itemsell.item.number);
        $('#erate').val(itemsell.rate);
        $('#eqty').val(itemsell.qty);
        $('#epaid').val(itemsell.paid);
        $('#etotal').val(itemsell.total);
        $('#edue').val(itemsell.due);
        $('#eid').val(itemsell.id);
        $('#editModal').modal('show');
    }

    function saveData() {
        if ($('#nepali-datepicker').val() == '' || $('#u_id').val() == '' || $('#item_id').val() == '' || $('#total').val() == 0) {
            alert('Please enter data in empty field !');
            $('#nepali-datepicker').focus();
            return false;
        } else {
            var bodyFormData = new FormData(document.getElementById('sellitemData'));
            axios({
                    method: 'post',
                    url: '{{ route("admin.sell.item.add")}}',
                    data: bodyFormData,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function(response) {
                    console.log(response.data);
                    showNotification('bg-success', 'Sellitem added successfully !');
                    $('#sellDataBody').prepend(response.data);
                    $('#u_id').val('');
                    $('#item_id').val('');
                    $('#rate').val('');
                    $('#qty').val(1);
                    $('#total').val(0);
                    $('#paid').val(0);
                    $('#due').val(0);
                    $('#u_id').focus();
                })
                .catch(function(error) {
                    showNotification('bg-danger', error.response.data);
                    //handle error
                    console.log(error.response);

                });
        }
    }

    function udateData() {
        if ($('#enepali-datepicker').val() == '' || $('#eu_id').val() == '' || $('#eitem_id').val() == '' || $('#etotal').val() == 0) {
            alert('Please enter data in empty field !');
            $('#enepali-datepicker').focus();
            return false;
        } else {
            var rowid = $('#eid').val();
            var bodyFormData = new FormData(document.getElementById('editform'));
            axios({
                    method: 'post',
                    url: '{{ route("admin.sell.item.update")}}',
                    data: bodyFormData,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function(response) {
                    console.log(response.data);
                    showNotification('bg-success', 'Sellitem updated successfully !');
                    $('#itemsell-'+rowid).replaceWith(response.data);
                    $('#u_id').val('');
                    $('#item_id').val('');
                    $('#rate').val('');
                    $('#qty').val(1);
                    $('#total').val(0);
                    $('#paid').val(0);
                    $('#due').val(0);
                    $('#editModal').modal('hide');
                })
                .catch(function(response) {
                    showNotification('bg-danger', 'You have entered invalid data !');
                    //handle error
                    console.log(response);
                });
        }
    }



    // delete

    function removeData(id) {
        if (confirm('Are you sure?')) {
            if(prompt("Type 'yes' To delete Data")=='yes'){
            data={
                'id':id,
                'date':$('#curdate').val()
            }
            axios({
                    method: 'post',
                    url: '{{route('admin.sell.item.delete')}}',
                    data:data
                })
                .then(function(response) {
                    showNotification('bg-danger', 'Sellitem deleted successfully!');
                    $('#itemsell-' + id).remove();
                })
                .catch(function(error) {
                    showNotification('bg-danger',error.response.data);

                    console.log(response)
                })
            }
        }
    }


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


    //XXX Select Framer From Clicking
    function farmerSelected(id) {
        $('#u_id').val(id);
        $('#u_id').focus();
    }

    //XXX Select Item From Search
    function itemSelected(data) {
       
        @if($large)
            $('#item_id').closeSearch();
        @endif
        $('#item_id').val(data.number);
        $('#rate').val(data.rate);
        $('#itemmodal').modal('hide');
        $('#rate').focus();
        $('#rate').select();
        calTotal();

    }

    //XXX Load Selling Data For Current Data And Collecion Center
    function loadData(){
        var center_id = $('#center_id').val();
        $('#sellDataBody').html('');
        $('#bill').addClass('d-none');
        axios.post('{{ route("admin.sell.item.list")}}',{'center_id':center_id,'date': $('#nepali-datepicker').val()})
        .then(function(response) {
            // console.log(response.data);
            $('#bill').removeClass('d-none');
            $('#sellDataBody').html(response.data);
        })
        .catch(function(error) {
            alert('error.response.data');
            //handle error
            // console.log(error);
            if(error.response){
                console.log(error.response.data);
                console.log(error.response.status);
                console.log(error.response.headers);
            }
        });
    }



   
    $('#nepali-datepicker').on('changed',function(){
        loadData();
    })
        // list
    

    window.onload = function() {
        
        bigScreen();
        $('#u_id').focus();
        // loaddata();
    };


    $(document).bind('keydown', 'alt+s', function(e){
       $('#itemmodal').modal('show');
    });

    $('#item_id').bind('keydown', 'alt+s', function(e){
       $('#itemmodal').modal('show');
    });

    // load farmer data by center id and call for sell data
    $('#center_id').change(function(){
        var center_id = $('#center_id').val();
        $('#_farmers').html("");
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
        loadData();
    })

    $('#paid').bind('keydown', 'return', function(e){
        saveData();
    });


    function selectAll(checked){
        $('.ids').each(function(){
            this.checked=checked;
        });
    }

    function deleteAll(){
        if(confirm('Do You Want Delete Selected Datas')){
            if(prompt("Type 'yes' To delete all Data")=='yes'){

                data=[];
                $('.ids').each(function(){
                    if(this.checked){
                        data.push(this.value);
                    }
                });

                axios({
                    method: 'post',
                    url: '{{ route("admin.sell.item.del-all-selitem")}}',
                    data:{'ids':data}
                })
                .then(function(response) {
                    $('#allchecked').prop("checked", false)
                    loadData();
                })
                .catch(function(response) {
                    //handle error
                    console.log(response);
                });
            }
        }


    }



</script>

<script>
    function renderBarcode(){
        html="<table>";
            this.data.forEach(item => {
                html+= '<tr class="search-item" id="item-'+ dotSanitize(item.number) +'" data-rate="'+item.sell_price+'" data-number="'+item.number+'" data-name="'+item.title+'" onclick="itemSelected(this.dataset);">'+
                    '<td class="p-1"><span style="cursor: pointer;">'+item.number+'</span></td>'+
                '</tr>';
                console.log(html, this.data);
            });
            html+="</table>"
            return html;
    }

    $('#item_id').search({
        url:'{{route('admin.item.barcode')}}',
        renderfunc:"renderBarcode",
        mod:"bar"
    });
</script>
@endsection
