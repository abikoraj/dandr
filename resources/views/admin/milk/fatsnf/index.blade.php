@extends('admin.layouts.app')
@section('title', 'Milk Data')
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
    <style>
        .selectable {
            cursor: pointer;

        }

        .selectable:hover {
            background: rgb(21, 21, 228);
            color: white;
        }
    </style>
@endsection
@section('head-title', 'Milk Data')
@section('toobar')

@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">
            <div id="_farmers">
                Select Collection center for load farmers !
            </div>
        </div>
        <div class="col-md-9 bg-light">
            <form action="" id="milkData">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date">Date</label>
                            <input type="text" name="date" id="nepali-datepicker" class="form-control next calender"
                                data-next="center_id" placeholder="Date">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date">Collection Center</label>
                            <select name="center_id" id="center_id" class="form-control show-tick ms next"
                                data-next="session">
                                <option></option>
                                @foreach (\App\Models\Center::all() as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="date">Session</label>
                            <select name="type" id="type" class="form-control show-tick ms next"
                                data-next="loaddata">
                                <option></option>
                                <option value="0">Morning</option>
                                <option value="1">Evening</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2  mt-4">
                        <input type="button" class="btn btn-primary btn-block next" data-next="u_id"
                            onkeydown="loadData();" onclick="loadData();" id="loaddata" value="Load">
                        {{-- <span>Load</span> --}}
                        <span class="btn btn-danger d-none" onclick="resetData()" id="resetdata"> Reset</span>
                    </div>

                    <div class="col-md-4 pr-0 add-section">
                        <input type="text" name="user_id" id="u_id" list="farmerdatalist" placeholder="number"
                            class="form-control checkfarmer next" data-next="amt" min="1">

                    </div>

                    <div class="col-md-2 p-0 add-section">
                        <input type="number" name="amt" id="amt" step="0.001" min="0.001" placeholder="Milk"
                            class="form-control next" data-next="fat">
                    </div>
                    <div class="col-md-2 p-0 add-section">
                        <input type="number" name="fat" id="fat" step="0.001" min="0.001" placeholder="Fat"
                            class="form-control next" data-next="snf">
                    </div>
                    <div class="col-md-2 p-0 add-section">
                        <input type="number" name="snf" id="snf" step="0.001" min="0.001" placeholder="SNF"
                            class="form-control next" data-next="saveData">
                    </div>

                    <div class="col-md-2 pl-0 add-section">
                        <input type="button" class="btn btn-primary btn-block" onclick="saveDate();" id="saveData"
                            value="Save">
                        {{-- <span >Save</span> --}}
                    </div>

                </div>
            </form>
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5" id="milkDataBody">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal -->
    <div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="title" id="defaultModalLabel">
                        The Milk Data for Farmer Has Been Already Added, Please Choose From Following Actions.
                    </h4>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="savedefault(0)">Update Current Data</button>
                    <button class="btn btn-primary" onclick="savedefault(1)">Add To Current Data</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->

@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('backend/js/pages/forms/advanced-form-elements.js') }}"></script>
    <script src="{{ asset('calender/nepali.datepicker.v3.2.min.js') }}"></script>
    <script>
        // $('#defaultModal').modal('show');
        $('.add-section').hide();
        // $( "#x" ).prop( "disabled", true );
        initTableSearch('sid', 'farmerData', ['name']);

        var saveLock = false;

        function saveDate() {
            if (saveLock) {
                return;
            }
            if ($('#u_id').val() == "") {
                alert('Please enter farmer number');
                $('#u_id').focus();
            } else if ($('#amt').val() == "") {
                alert('Please enter milk amout');
                $('#amt').focus();
            } else if ($('#snf').val() != "" && $('#fat').val() == "") {
                alert('Please enter fat');
                $('#fat').focus();

            } else if ($('#snf').val() == "" && $('#fat').val() != "") {
                alert('Please enter SNF');
                $('#snf').focus();
            } else {
                var no = $('#u_id').val();
                if (document.querySelectorAll('#milk-' + no).length > 0) {
                    if (!(prompt("Milk Data Already Added for this session,Do you want to update data?")=='yes')) {
                        return;
                    }
                }
                saveLock = true;
                // showProgress('Saving Milk Data');
                var fdata = new FormData(document.getElementById('milkData'));
                $('#u_id').val('');
                $('#snf').val('');
                $('#fat').val('');
                $('#amt').val('');
                $('#u_id').focus();
                axios({
                        method: 'post',
                        url: '{{ route('admin.milk.milkfatsnfSave') }}',
                        data: fdata,
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(function(response) {
                        if (exists('#milk-' + no)) {
                            $('#milk-' + no).replaceWith(response.data);
                        } else {

                            $('#milk-datas').prepend(response.data);
                        }
                        calculateTotal();

                        saveLock = false;
                        //    hideProgress();

                    })
                    .catch(function(response) {
                        //handle error
                        alert(response);
                        console.log(response);
                        saveLock = false;

                    });
            }
        }

        function del(no){
            const ele=$('#milk-'+no)[0];
            if(prompt("Please enter yes to delete")=="yes"){
                const data={
                    milkdata_id:ele.dataset.milkdata_id,
                    snffat_id:ele.dataset.snffat_id,
                    type:$('#type').val()
                };
                showProgress("Deleting Milk Data");
                axios.post('{{route('admin.milk.milkfatsnfDel')}}',data)
                .then((res)=>{
                    $('#milk-'+no).remove();
                    hideProgress();
                    calculateTotal();
                })
                .catch((err)=>{
                    hideProgress();
                    
                });
            }
        }

        function disableTopPanel(method) {
            $('#nepali-datepicker').css('pointer-events', method);
            $('#center_id').css('pointer-events', method);
            $('#type').css('pointer-events', method);
            $('#loaddata').css('pointer-events', method);
            if (method == 'none') {
                $('#resetdata').removeClass('d-none').addClass('d-block');
            } else {
                $('#resetdata').removeClass('d-block').addClass('d-none');

            }
        }

        function resetData() {
            $('#milkDataBody').empty();
            //    $('#milkData')[0].reset();
            disableTopPanel('auto');
            $('.add-section').hide();
            $('#loaddata').show();

        }


        function loadData() {
            disableTopPanel('none');
            $('#loaddata').hide();
            if ($('#nepali-datepicker').val() == '' || $('#center_id').val() == '' || $('#session').val() == '') {
                alert('Please fill empty field !');
                if ($('#nepali-datepicker').val() == '') {
                    $('#nepali-datepicker').focus();
                    return false;
                } else if ($('#center_id').val() == '') {
                    $('#center_id').focus();
                    return false;
                } else {
                    $('#session').focus();
                    return false;
                }
                disableTopPanel('auto');
            } else {
                var fdata = new FormData(document.getElementById('milkData'));
                // store.milk
                $('.add-section').show();
                axios({
                        method: 'post',
                        url: '{{ route('admin.milk.milkfatsnf') }}',
                        data: fdata,
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(function(response) {
                        // console.log(response.data);
                        $('#milkDataBody').empty();
                        $('#milkDataBody').append(response.data);
                        calculateTotal();
                    })
                    .catch(function(response) {
                        //handle error
                        console.log(response);
                    });
            }
        }

        function farmerSelected(id) {
            $('#u_id').val(id);
            $('#u_id').focus();
        }


        function calculateTotal() {
            let milk = 0;

            $('.milkdata').each(function(index, element) {

                const amt = parseFloat(element.innerText);
                milk += isNaN(amt) ? 0 : amt;
                milk=Number(milk.toFixed(2));

            });

        
            $('#total').html(milk.toString());
        }

        window.onload = function() {

            $('#center_id').focus();
        };

        $('#snf').bind('keydown', 'return', function(e) {
            saveDate(e);
        });

        // load farmer data by center id
        $('#center_id').change(function() {
            var center_id = $('#center_id').val();
            axios({
                    method: 'post',
                    url: '{{ route('admin.farmer.minlist-bycenter') }}',
                    data: {
                        'center': center_id
                    }
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
