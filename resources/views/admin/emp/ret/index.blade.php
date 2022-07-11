@extends('admin.layouts.app')
@section('title','Employee Return')
@section('css')
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('head-title','Employee Return')
@section('toobar')
@endsection
@section('content')
<div class="row">
<div class="col-lg-12">

    <form id="form_validation" method="POST" onsubmit="return saveData(event);">
        @csrf
        <div class="row">

            <div class="col-lg-3">
                <label for="date">Date</label>
                <input type="text" name="date" id="nepali-datepicker" class="form-control next" data-next="u_id" changed="console.log('event')">
            </div>

            <div class="col-lg-4">
                <label for="u_number">Employee</label>

                <div class="form-group">
                   <select name="employee_id" id="employee_id" class="form-control show-tick ms select2">
                        <option ></option>
                        @foreach (\App\Models\Employee::all() as $employee)
                                @if (isset($employee->user))
                                   <option value="{{$employee->id}}">
                                       {{ $employee->user->name }}
                                    </option>
                                @endif
                        @endforeach
                   </select>
                </div>
            </div>

            <div class="col-lg-5">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control next" data-next="amount" placeholder="Enter title" required>
            </div>

            <div class="col-lg-3">
                <label for="amount">Return Amount</label>
                <input type="number" id="amount" min="0" name="amount" class="form-control next xpay_handle" data-next="save" placeholder="Enter Return amount" value="0" required>
            </div>
            <div class="col-lg-2">
                <input type="submit" id="save" class="btn btn-raised btn-primary waves-effect btn-block" value="Add" style="margin-top:30px;">
            </div>
            <div class="col-12">
                <div class="row">
                    @include('admin.payment.take')
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
                <th>Title</th>
                <th>Employee</th>
                <th>Amount (Rs.)</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="advanceData">

        </tbody>
    </table>
</div>


<!-- edit modal -->


{{-- <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-ff="eamount">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Edit Farmer</h4>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                    <form id="editform" onsubmit="return editData(event);">
                        @csrf
                        <input type="hidden" name="id" id="eid">
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="u_number">Farmer Number</label>
                                <input type="text" name="date" id="enepali-datepicker">
                                <div class="form-group">
                                    <input type="number" id="eu_id" name="user_id" min="0" class="form-control next" data-next="amount" placeholder="Enter farmer number" required readonly>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="amount">Return Amount</label>
                                <input type="number" id="eamount" min="0" name="amount" class="form-control xpay_handle" placeholder="Enter Return amount" value="0" required>
                            </div>
                            <div class="col-lg-12">
                                <button class="btn btn-raised btn-primary waves-effect btn-block" type="submit">Submit Data</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}

@endsection
@section('js')
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('calender/nepali.datepicker.v3.2.min.js') }}"></script>
<script>
    // initTableSearch('searchid', 'farmerforData', ['name']);
    // load by date
    $("input#nepali-datepicker").bind('click', function (e) {
        loadAdvance();
    });


    $("input#nepali-datepicker").bind('changed', function (e) {
        loadAdvance();
    });

    function loadAdvance(){
        var date = $('#nepali-datepicker').val();
        showProgress("Loading Advance Return");
        axios({
                method: 'post',
                url: '{{ route("admin.employee.ret.list")}}',
                data : {'date' : date}
            })
            .then(function(response) {
                // console.log(response.data);
                $('#advanceData').empty();
                $('#advanceData').html(response.data);
                hideProgress();
            })
            .catch(function(err) {
                //handle error
                console.log(err);
                showNotification('bg-danger',err.response.data);
                hideProgress();

        });
    }

    function update(ele,e,id){
        var date = $('#nepali-datepicker').val();
        e.preventDefault();
        showProgress('Updating Advance Return');
        axios({
                method: 'post',
                url: ele.action,
                data : new FormData(ele)
            })
            .then(function(response) {
                showNotification('bg-success', 'Updated successfully!');
                $('#advancerow-'+id).replaceWith(response.data);
                hideProgress();
                win.hide();
            })
            .catch(function(err) {
                //handle error
                console.log(err);
                if(err.response){

                    showNotification('bg-danger',err.response.data.message);
                }
                hideProgress();


            });
    }

    function del(id){
        var date = $('#nepali-datepicker').val();
        if (confirm('Are you sure?')) {
            showProgress('Deleting Advance Return');
        axios({
                method: 'post',
                url: '{{ route("admin.employee.ret.del")}}',
                data : {
                    'date' : date,
                    'id':id
                }
            })
            .then(function(response) {
                showNotification('bg-success', 'Deleted successfully!');
                $('#advancerow-'+id).remove();
                hideProgress();
            })
            .catch(function(err) {
                //handle error
                console.log(err);
                if(err.response){
                    showNotification('bg-danger', 'error : '+err.response.data.message);
                }

                hideProgress();

            });
        }
    }



    function initUpdate(id){
        win.showPost('Update Advance Return',"{{route('admin.employee.ret.edit')}}",{id:id},addEXPayHandle );
    }

    function saveData(e) {
        e.preventDefault();
        if($('#title').val()=="" || $('#amount').val()==0){
            alert('Please enter empty field!');
            $('#title').focus();
            return false;
        }else{
        var bodyFormData = new FormData(document.getElementById('form_validation'));
        axios({
                method: 'post',
                url: '{{ route("admin.employee.ret.add")}}',
                data: bodyFormData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                console.log(response);
                showNotification('bg-success', 'Employee Return added successfully!');
                $('#advanceData').prepend(response.data);
                $('#u_id').val('');
                $('#amount').val(0);
                $('#xpay_amount').val(0);
                $('#title').val('');
                $('#employee_id').val(null);
                $('#u_id').focus();
            })
            .catch(function(err) {
                //handle error
                console.log(err);
                showNotification('bg-danger',err.response.data);
            });

        }
    }


    window.onload = function() {
        var mainInput = document.getElementById("nepali-datepicker");
        mainInput.nepaliDatePicker();
        var month = ('0'+ NepaliFunctions.GetCurrentBsDate().month).slice(-2);
        var day = ('0' + NepaliFunctions.GetCurrentBsDate().day).slice(-2);
        $('#nepali-datepicker').val(NepaliFunctions.GetCurrentBsYear() + '-' + month + '-' + day);
        loadAdvance();
        $('#u_id').focus();
    };




</script>
@endsection
