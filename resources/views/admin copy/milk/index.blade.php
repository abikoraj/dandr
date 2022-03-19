@extends('admin.layouts.app')
@section('title','Milk Data')
@section('css')
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
<style>
    .selectable{
        cursor: pointer;

    }

    .selectable:hover{
        background:rgb(21, 21, 228);
        color:white;
    }
</style>
@endsection
@section('head-title','Milk Data')
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
                        <input type="text" name="date" id="nepali-datepicker" class="form-control next calender" data-next="center_id" placeholder="Date">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date">Collection Center</label>
                        <select name="center_id" id="center_id" class="form-control show-tick ms next" data-next="session">
                            <option></option>
                            @foreach(\App\Models\Center::all() as $c)
                            <option value="{{$c->id}}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date">Session</label>
                        <select name="session" id="session" class="form-control show-tick ms next" data-next="loaddata">
                            <option></option>
                            <option value="0">Morning</option>
                            <option value="1">Evening</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2 mt-4">
                    <input type="button" class="btn btn-primary btn-block next" data-next="u_id" onkeydown="loadData();" onclick="loadData();" id="loaddata" value="Load">
                    {{-- <span>Load</span> --}}
                    <span class="btn btn-danger d-none" onclick="resetData()" id="resetdata"> Reset</span>
                </div>

                <div class="col-md-4 add-section">
                    <input type="number" name="user_id" id="u_id" placeholder="number" class="form-control checkfarmer next" data-next="m_amt" min="1">
                </div>

                <div class="col-md-4 add-section">
                    <input type="number" name="milk_amount" id="m_amt" step="0.001" min="0.001" placeholder="Milk in liter" class="form-control next" data-next="saveData" >
                </div>

                <div class="col-md-4 add-section">
                    <input type="button" class="btn btn-primary btn-block" onclick="saveDate();" id="saveData" value="Save">
                    {{-- <span >Save</span> --}}
                </div>

            </div>
        </form>
        <div class="row">
            <div class="col-md-12">
                <div class="mt-5">
                    <table id="newstable1" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#No</th>
                                <th>Morning Milk (In Liter)</th>
                                <th>Evening Milk (In Liter)</th>
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
    function savedefault(type){
        var url='{{ route("admin.milk.store",["type"=>0])}}';
        if(type==1){
            url='{{ route("admin.milk.store",["type"=>1])}}';
        }
        var id=$('#u_id').val();
        var amount=$('#m_amt').val();
        var session =$('#session').val();
        var fdata = new FormData(document.getElementById('milkData'));
        // store.milk
        axios({
                method: 'post',
                url:url,
                data: fdata,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                console.log(response.data);
                // showNotification('bg-success', 'Milk data added Successfully !');
                $('#u_id').val('');
                $('#m_amt').val('');

                if(session==0){
                        document.querySelector('#milk-'+id).dataset.m_amount=response.data.m_amount;
                        $('#m_milk-'+id).text(response.data.m_amount);
                }else{
                        document.querySelector('#milk-'+id).dataset.e_amount=response.data.e_amount;
                        $('#e_milk-'+id).text(response.data.e_amount);
                }
                $('#defaultModal').modal('hide');

            })
            .catch(function(response) {
                //handle error
                console.log(response);
            });
    }


    function saveDate() {
        if($('#u_id').val()==""){
            alert('Please enter farmer number');
            $('#u_id').focus();
        }else if($('#m_amt').val()==""){
            alert('Please enter milk amout');
            $('#m_amt').focus();
        }else{
            var id=$('#u_id').val();
            var amount=$('#m_amt').val();
            var session =$('#session').val();
            // alert(document.querySelectorAll('#milk-'+id).length);
            // return;
            if(document.querySelectorAll('#milk-'+id).length>0){
                m_milk=document.querySelector('#milk-'+id).dataset.m_amount;
                e_milk=document.querySelector('#milk-'+id).dataset.e_amount;
                if(session==0){
                    if(m_milk>0){
                        $('#defaultModal').modal('show');
                        return;
                    }
                }else{
                    if(e_milk>0){
                        $('#defaultModal').modal('show');
                        return;
                    }
                }
            }
        var fdata = new FormData(document.getElementById('milkData'));
        // store.milk
        axios({
                method: 'post',
                url: '{{ route("admin.milk.store",["type"=>0])}}',
                data: fdata,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                console.log(response.data);
                $('#u_id').val('');
                $('#m_amt').val('');
                if(document.querySelectorAll('#milk-'+id).length>0){
                    if(session==0){
                        document.querySelector('#milk-'+id).dataset.m_amount=response.data.m_amount;
                        $('#m_milk-'+id).text(response.data.m_amount);
                    }else{
                        document.querySelector('#milk-'+id).dataset.e_amount=response.data.e_amount;
                        $('#e_milk-'+id).text(response.data.e_amount);
                    }
                }else{
                    $('#milkDataBody').prepend(response.data);
                }
                $('#u_id').focus();
                // showNotification('bg-success', 'Milk data added Successfully !');

            })
            .catch(function(response) {
                //handle error
                alert(response);
                console.log(response);
            });
        }
    }

    function disableTopPanel(method){
        $('#nepali-datepicker').css('pointer-events',method);
        $('#center_id').css('pointer-events',method);
        $('#session').css('pointer-events',method);
        $('#loaddata').css('pointer-events',method);
        if(method=='none'){
            $('#resetdata').removeClass('d-none').addClass('d-block');
        }else{
            $('#resetdata').removeClass('d-block').addClass('d-none');

        }
    }

    function resetData(){
        $('#milkDataBody').empty();
       $('#milkData')[0].reset();
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
                    url: '{{ route("admin.milk.load")}}',
                    data: fdata,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function(response) {
                    // console.log(response.data);
                    $('#milkDataBody').empty();
                    $('#milkDataBody').append(response.data);
                })
                .catch(function(response) {
                    //handle error
                    console.log(response);
                });
        }
    }

    function farmerSelected(id){
        $('#u_id').val(id);
        $('#u_id').focus();
    }

  

    window.onload = function() {
        
        $('#center_id').focus();
    };

    $('#m_amt').bind('keydown', 'return', function(e){
       saveDate(e);
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
