@extends('admin.layouts.app')
@section('title','Snf & Fat')
@section('css')
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('head-title','Snf & Fats')
@section('toobar')

@endsection
@section('content')
@include('admin.snf.update')
<div class="row">
    <div class="col-md-3">
        <div id="_farmers">
            Select Collection center for load farmers !
        </div>
    </div>

    <div class="col-md-9 bg-light">
        <form action="" id="snffats">
            @csrf
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="text" name="date" id="nepali-datepicker" class="calender form-control" placeholder="Date">
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <label for="date">Collection Center</label>
                        <select name="center_id" id="center_id" class="form-control show-tick ms next" data-next="loaddata">
                            <option></option>
                            @foreach(\App\Models\Center::all() as $c)
                            <option value="{{$c->id}}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2 mt-4">
                    <input type="button" class="btn btn-primary btn-block next" data-next="u_id" onclick="loadData();"  onkeydown="loadData();" id="loaddata" value="Load">
                    {{-- <span >Load</span> --}}
                    <span class="btn btn-danger d-none" onclick="resetData()" id="resetdata"> Reset</span>
                </div>
                <div class="col-md-3 add-section">
                    <input type="number" name="user_id" id="u_id" placeholder="number" class="form-control checkfarmer next" data-next="fat" min="1">
                </div>

                <div class="col-md-3 add-section">
                    <input type="number" name="fat" id="fat" step="0.001" min="0.001" placeholder="Fats" class="form-control next" data-next="snf" >
                </div>

                <div class="col-md-3 add-section">
                    <input type="number" name="snf" id="snf" step="0.001" min="0.001" placeholder="Snf" class="form-control next" data-next="saveData">
                </div>

                <div class="col-md-3 add-section">
                    <input type="button" class="btn btn-primary btn-block" onclick="saveDate();" value="Save" id="saveData">
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
                                <th>Fats (%)</th>
                                <th>Snf (%)</th>
                            </tr>
                        </thead>
                        <tbody id="snffatBody">

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
                    The Snf & Fat Data for Farmer Has Been Already Added, Please Choose From Following Actions.
                </h4>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="savedefault()">Update Current Data</button>
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
<script>
    initTableSearch('sid', 'farmerData', ['name']);
    $('.add-section').hide();

    function farmerSelected(id) {
        $('#u_id').val(id);
        $('#u_id').focus();
    }

    function disableTopPanel(method) {
        $('#nepali-datepicker').css('pointer-events', method);
        $('#center_id').css('pointer-events', method);

        if (method == 'none') {
            $('#resetdata').removeClass('d-none').addClass('d-block');
        } else {
            $('#resetdata').removeClass('d-block').addClass('d-none');

        }
    }

    function resetData() {
        $('#snffatBody').empty();
        $('#snffats')[0].reset();
        disableTopPanel('auto');
        $('.add-section').hide();
        $('#loaddata').show();
    }

    function loadData() {
        disableTopPanel('none');
        $('#loaddata').hide();
        if ($('#nepali-datepicker').val() == '' || $('#center_id').val() == '' ) {
            alert('Please fill empty field !');
            if ($('#nepali-datepicker').val() == '') {
                $('#nepali-datepicker').focus();
                return false;
            } else if ($('#center_id').val() == '') {
                $('#center_id').focus();
                return false;
            }
            disableTopPanel('auto');
        } else {
            var fdata = new FormData(document.getElementById('snffats'));
            // store.milk
            $('.add-section').show();
            axios({
                    method: 'post',
                    url: '{{ route("admin.snf-fat.load-data")}}',
                    data: fdata,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function(response) {
                    // console.log(response.data);
                    $('#snffatBody').empty();
                    $('#snffatBody').append(response.data);
                })
                .catch(function(response) {
                    //handle error
                    console.log(response);
                });
        }
    }



    function saveDate() {
        if($('#fat').val()==''){
            alert('Please enter fat percentage');
            $('#fat').focus();
            return false;
        }else if($('#snf').val()==''){
            alert('Please enter snf percentage');
            $('#snf').focus();
            return false;
        }else{
            var id=$('#u_id').val();

            if(document.querySelectorAll('#snf-'+id).length>0){
                _snf=document.querySelector('#snf-'+id).dataset.snf;
                _fat=document.querySelector('#snf-'+id).dataset.fat;

                    if(_snf>0){
                        $('#defaultModal').modal('show');
                        return;
                    }

                    if(_fat>0){
                        $('#defaultModal').modal('show');
                        return;
                    }
            }
        var fdata = new FormData(document.getElementById('snffats'));
        // store.milk
        axios({
                method: 'post',
                url: '{{ route("admin.snf-fat.store")}}',
                data: fdata,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                console.log(response.data);
                // showNotification('bg-success', 'Data added Successfully !');
                $('#snffatBody').prepend(response.data);
                $('#snf').val('');
                $('#fat').val('');
                $('#u_id').val('');
                $('#u_id').focus();
            })
            .catch(function(response) {
                //handle error
                console.log(response);
            });
        }
    }

    function savedefault(){
        var id=$('#u_id').val();
        var fdata = new FormData(document.getElementById('snffats'));
        // store.milk
        axios({
                method: 'post',
                url: '{{ route("admin.snf-fat.store")}}',
                data: fdata,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                console.log(response.data);
                // showNotification('bg-success', 'Data updated Successfully !');
                document.querySelector('#snf-'+id).dataset.snf=response.data.snf;
                $('#_snf-'+id).text(response.data.snf);

                document.querySelector('#snf-'+id).dataset.fat=response.data.fat;
                $('#_fat-'+id).text(response.data.fat);
                $('#defaultModal').modal('hide');
                $('#snf').val('');
                $('#fat').val('');
                $('#u_id').val('');
                $('#u_id').focus();
            })
            .catch(function(response) {
                //handle error
                console.log(response);
            });
    }

   

    window.onload = function() {
       
        $('#center_id').focus();
    };

    $('#snf').bind('keydown', 'return', function(e){
       saveDate();
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

    function snfUpdated(data){
        console.log(data);
        $("#"+data.id+"_snf").html(data.snf);
        $("#"+data.id+"_fat").html(data.fat);
    }

    function snfDeleted(data){
        console.log('datadeleted',data);
        $("#snffat_"+data.id).remove();
    }
</script>

@endsection

<!-- reward per items -->
<!-- reward amt ,reward per=> sell items  -->
