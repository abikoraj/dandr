@extends('admin.layouts.app')
@section('title', 'Distributer Milk Sell')
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

    </style>
@endsection
@section('head-title', 'Distributer Milk Sell')
@section('toobar')

@endsection
@section('content')
    <div class="row">
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
                    @foreach (\App\Models\Distributer::get() as $d)
                        @if ($d->user != null)
                            <tr style="cursor:pointer;" onclick="setData('id',{{ $d->id }})"
                                id="dis-{{ $d->id }}" data-name="{{ $d->user->name }}" class="searchable">
                                <td>
                                    {{ $d->id }}
                                </td>
                                <td>
                                    {{ $d->user->name }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-9 bg-light pt-2">
            <form action="" id="sellMilkData">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input readonly type="text" name="date" id="nepali-datepicker"
                                class="calender form-control next" data-next="user_id" placeholder="Date">
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date">Session</label>
                            <select name="session" id="session" class="form-control show-tick ms next" data-next="loaddata">
                                <option value="0">Any</option>
                                <option value="1">Morning</option>
                                <option value="2">Evening</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 mt-4">
                        <input type="button" class="btn btn-primary btn-block next" data-next="u_id" onkeydown="loadData();"
                            onclick="loadData();" id="loaddata" value="Load">
                        {{-- <span>Load</span> --}}
                        <span class="btn btn-danger " onclick="resetData()" id="resetdata"> Reset</span>
                    </div>
                    <div class="col-md-12 add-section">
                        <hr>
                    </div>
                    <div class="col-md-3 add-section">
                        <div class="form-group">
                            <label for="id">Distributer</label>
                            <input type="number" id="id" name="id" class="form-control next" data-next="product_id">
                        </div>
                    </div>
                    <div class="col-md-4 add-section">
                        <label for="id">Milk Quantity</label>

                        <input type="number" name="amount" id="amount" step="0.001" min="0.001"
                            placeholder="Milk in liter" class="form-control next" data-next="saveData">
                    </div>
                    <div class="col-md-3 mt-4 add-section">
                        <input type="button" value="Save" class="btn btn-primary btn-block" onclick="saveData();" id="save">
                        {{-- <span class="btn btn-primary btn-block" >Save</span> --}}
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
                                    <th>Quantity (ℓ)</th>
                                    <th>Session</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="milkDataBody">

                            </tbody>
                        </table>
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
        initTableSearch('sId', 'sellDisDataBody', ['name']);
        initTableSearch('s_dis', 'distributors', ['name']);
        $('.add-section').hide();
        $('#resetdata').hide();

        function resetData() {
            $('#milkDataBody').empty();
            $('.add-section').hide();
            $('#loaddata').show();
            $('#resetdata').hide();

        }

        sellock = false;

        function saveData() {
            if (!sellock) {

                if ($('#nepali-datepicker').val() == '' || $('#id').val() == '' || $('#amount').val() == '' || $('#amount').val() == 0) {
                    alert('Please enter data in empty field !');
                    $('#id').focus();
                    return false;
                } else {

                    sellock = true;
                    var bodyFormData = new FormData(document.getElementById('sellMilkData'));
                    axios({
                            method: 'post',
                            url: '{{ route('admin.distributer.MilkData.add') }}',
                            data: bodyFormData,
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then(function(response) {
                            console.log(response.data);
                            // showNotification('bg-success', 'Sellitem added successfully !');
                            $('#milkDataBody').prepend(response.data);
                            $('#id').val('');
                            $('#amount').val('');
                            $('#id').focus();
                            showNotification('bg-success', 'Milk Data added successfully !');
                            sellock = false;
                        })
                        .catch(function(response) {
                            sellock = false;

                            showNotification('bg-danger', 'You have entered invalid data !');
                            //handle error
                            console.log(response);
                        });
                }
            }
        }

        // update
        function update(id){
            if(confirm('Do You Want to Update Milk Data?')){
                axios.post('{{route('admin.distributer.MilkData.update')}}',{
                    "id":id,
                    "amount":$('#amount-'+id).val()
                })
                .then((res)=>{
                    showNotification('bg-success', 'Milk Data Updated successfully !');
                })
                .catch((err)=>{
                    showNotification('bg-danger', 'Cannot update Milk Data,Please Try again ');

                });
            }
        }

         // delete
         function del(id){
            if(confirm('Do You Want to Delete Milk Data?')){
                axios.post('{{route('admin.distributer.MilkData.delete')}}',{
                    "id":id,
                })
                .then((res)=>{
                    showNotification('bg-success', 'Milk Data Deleted successfully !');
                    $('#milkdata-'+id).remove();
                })
                .catch((err)=>{
                    showNotification('bg-danger', 'Cannot Delete Milk Data,Please Try again ');

                });
            }
        }




        function loadData() {
            $('#milkDataBody').html("");
            showProgress('Loading Milk Data.')

            // list
            axios.post('{{ route('admin.distributer.MilkData.index') }}', {
                    'date': $('#nepali-datepicker').val(),
                    'session': $('#session').val()
                })
                .then(function(response) {
                    // console.log(response.data);
                    $('#milkDataBody').html(response.data);
                    $('#loaddata').hide();
                    $('#resetdata').show();
                    $('.add-section').show();
                    hideProgress();
                })
                .catch(function(response) {
                    hideProgress();
                    //handle error
                    console.log(response);
                });
        }


        window.onload = function() {

            $('#id').focus();
        };

        $('#id').focusout(function() {
            if ($(this).val() != "") {

                if (!exists('#dis-' + $(this).val())) {
                    alert('Distributor Not Found');
                    $(this).focus();
                    $(this).select();
                    $(this).val("");
                }
            }
        });

        $('#nepali-datepicker').bind('changed', function() {
            loaddata();
        });
    </script>
@endsection
