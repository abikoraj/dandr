@extends('admin.layouts.app')
@section('title','Farmer Advance')
@section('css')
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('head-title','Farmer Advance')
@section('toobar')
@endsection
@section('content')
<div class="row">
<div class="col-lg-12">
    @include('admin.farmer.farmermodal')
    <form id="form_validation" method="POST" onsubmit="return saveData(event);">
        @csrf
        <div class="row">
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
            <div class="col-lg-3">
                <label for="date">Date</label>
                <input type="text" name="date" id="nepali-datepicker" class="form-control next calender" data-next="u_id">
            </div>
            <div class="col-lg-3">
                <span class="btn btn-primary w-100" onclick="loadData();" style="margin-top:30px;">Load Data</span>
            </div>
            <div class="col-md-12"><hr></div>

            <div class="col-lg-4">
                <label for="u_number">Farmer Number</label>
                <span id="farmersearch"  data-toggle="modal" data-target="#farmermodal" style="cursor: pointer;">( search (alt+s) )</span>
                <div class="form-group">
                    <input type="text" id="u_id" name="no" list="farmerdatalist" min="0" class="form-control next checkfarmer" data-next="amount" placeholder="Enter farmer number" required>
                </div>
            </div>

            <div class="col-lg-3">
                <label for="amount">Advance Amount</label>
                <input type="number" id="amount" min="0" oninput="this.value = Math.abs(this.value)" name="amount" class="form-control next xpay_handle" data-next="save" placeholder="Enter advance amount" value="0" required>
            </div>
            <div class="col-lg-2">
                <input type="submit" id="save" class="btn btn-raised btn-primary waves-effect btn-block" value="Add" style="margin-top:30px;">
            </div>
            <div class="col-12"></div>

            @include('admin.payment.take',['xpay_type'=>2])


        </div>
    </form>
</div>
</div>
<div class="pt-2 pb-2">
    <input type="text" id="sid" placeholder="Search">
</div>
<div class="table-responsive">
    <table id="newstable1" class="table table-bordered table-striped table-hover js-basic-example dataTable">
        <thead>
            <tr>
                <th>Farmer Number</th>
                <th>Farmer Name</th>
                <th>Amount (Rs.)</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="advanceData">

        </tbody>
    </table>
</div>
@include('admin.farmer.advance.edit')

@endsection
@section('js')
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('calender/nepali.datepicker.v3.2.min.js') }}"></script>
<script>
    initTableSearch('searchid', 'farmerforData', ['name']);
    // load by date
    $("input#nepali-datepicker").bind('click', function (e) {
        loadData();
    });

    function loadData(){
        var date = $('#nepali-datepicker').val();
        var center_id = $('#center_id').val();
        axios({
                method: 'post',
                url: '{{ route("admin.farmer.advance.list")}}',
                data : {'date' : date,'center_id':center_id}
            })
            .then(function(response) {
                // console.log(response.data);
                $('#advanceData').empty();
                $('#advanceData').html(response.data);
            })
            .catch(function(response) {
                //handle error
                console.log(response);
            });
    }

    function initEdit(ele) {
        var adv = JSON.parse(ele.dataset.advance);
        console.log(adv);
        $('#eid').val(adv.id);
        $('#eamount').val(adv.amount);
        $('#expay_amount').val(adv.amount);
        $('#editModal').modal('show');
        loadXPayEdit(adv.id,104);

    }

    function saveData(e) {
        e.preventDefault();
        if(!xpayVerifyData()){
            return;
        }
        var bodyFormData = new FormData(document.getElementById('form_validation'));
        axios({
                method: 'post',
                url: '{{ route("admin.farmer.advance.add")}}',
                data: bodyFormData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                console.log(response.data);
                if(response.data == "notOk"){
                    showNotification('bg-danger', 'Previous session is not closed yet!');
                    return;
                }
                showNotification('bg-success', 'Farmer advance added successfully!');
                $('#largeModal').modal('toggle');
                $('#advanceData').prepend(response.data);
                $('#u_id').val('');
                $('#amount').val(0);
                $('#u_id').focus();
                resetXPayment();
            })
            .catch(function(response) {
                //handle error
                console.log(response);
                showNotification('bg-danger','operation Faild!');
            });
    }

    // edit data
    function editData(e) {
        e.preventDefault();
        var rowid = $('#eid').val();
        if(!expayVerifyData()){
            return;
        }
        var bodyFormData = new FormData(document.getElementById('editform'));
        axios({
                method: 'post',
                url: '{{route('admin.farmer.advance.update')}}',
                data: bodyFormData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                console.log(response);
                showNotification('bg-success', 'Updated successfully!');
                $('#editModal').modal('toggle');
                $('#advance-' + rowid).replaceWith(response.data);
            })
            .catch(function(response) {
                //handle error
                console.log(response);
            });
    }


    // delete
    function removeData(id) {
        var dataid = id;
        console.log(dataid);
        if (confirm('Are you sure?')) {
            axios({
                    method: 'post',
                    url: '{{route('admin.farmer.advance.delete')}}',
                    data:{"id":id}
                })
                .then(function(response) {
                    // console.log(response.data);
                    $('#advance-' + dataid).remove();
                    showNotification('bg-danger', 'Deleted Successfully !');
                })
                .catch(function(response) {
                    showNotification('bg-danger','You do not have authority to delete!');
                    //handle error
                    console.log(response);
                });
        }
    }

    // load advance
   $('#center_id').change(function(){
        loadData();
   })

    window.onload = function() {

        $('#u_id').focus();
        // loadAdvance();
    };


    function farmerSelected(id){
        $('#u_id').val(id);
        $('#farmermodal').modal('hide');
        $('#u_id').focus();
    }

    $(document).bind('keydown', 'alt+s', function(e){
       $('#farmermodal').modal('show');
    });
    $('#u_id').bind('keydown', 'alt+s', function(e){
       $('#farmermodal').modal('show');
    });

    // load farmer data by center id
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
