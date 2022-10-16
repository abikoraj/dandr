@extends('admin.layouts.app')
@section('title','Farmer-List')
@section('head-title','Farmer-List')
@section('css')
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
<style>
    .ck:focus-within{
        border: 1px solid #007ACC;
    }
</style>
@endsection
@section('toobar')
@if (auth_has_per('01.02'))
    <button type="button" class="btn btn-primary waves-effect m-r-20" data-toggle="modal" data-target="#largeModal">Create Farmer (alt+n)</button>
@endif
@endsection
@section('content')
<div class="row">
    <div class="col-md-3">
        <input type="text" id="sid" placeholder="Search">
    </div>
    <div class="col-md-3"></div>
    <div class="col-md-3 text-right"><div class="mt-2"><strong> Collection Center : </strong></div></div>
    <div class="col-md-3">
        <div class="form-group text-right">
            <select name="center_id" id="loadFarmerByCenter" class="form-control show-tick ms next" data-next="session">
                <option>Select A Collection Center</option>
                @foreach(\App\Models\Center::all() as $c)
                <option value="{{$c->id}}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table id="newstable1" class="table table-bordered table-striped table-hover js-basic-example dataTable">
        <thead>
            <tr>
                <th>#Id</th>
                <th>Farmer Name</th>
                @if(env('requirephone',1)==1)
                    <th>Farmer phone</th>
                @endif
                <th>Farmer Address</th>
                {{-- <th>Balance (Rs.)</th> --}}
                <th></th>
            </tr>
        </thead>
        <tbody id="farmerData">

        </tbody>
    </table>
</div>
@includeWhen(auth_has_per('01.01'),'admin.farmer.add')
@includeWhen(auth_has_per('01.03'),'admin.farmer.edit')



@endsection
@section('js')
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script>

    function initEdit(ele) {
        var farmer = JSON.parse(ele.dataset.farmer);
        console.log(farmer);
        $('#ecenter_id').val(farmer.center_id).change();
        $('#ename').val(farmer.name);
        $('#eno').val(farmer.no);
        $('#ephone').val(farmer.phone);
        $('#eaddress').val(farmer.address);
        @if(env('usecc',0)==1)
            $('#eusecc')[0].checked=farmer.usecc==1;
        @endif
        @if(env('usecc',0)==1)
            $('#eusetc')[0].checked=farmer.usetc==1;
            $('#euse_ts_amount')[0].checked=farmer.use_ts_amount==1;
            $('#ets_amount').val(farmer.ts_amount).change();
        @endif
        @if(env('useprotsahan',0)==1)
            $('#euse_protsahan')[0].checked=farmer.use_protsahan==1;
            $('#eprotsahan').val(farmer.protsahan).change();
        @endif
        @if(env('usetransportamount',0)==1)
            $('#euse_transport')[0].checked=farmer.use_transport==1;
            $('#etransport').val(farmer.transport).change();
        @endif

        $('#euserate')[0].checked=farmer.userate==1;
        $('#euse_custom_rate')[0].checked=farmer.use_custom_rate==1;
        $('#erate').val(farmer.rate).change();
        $('#efat_rate').val(farmer.fat_rate).change();
        $('#esnf_rate').val(farmer.snf_rate).change();
        // $('#eadvance').val(ele.dataset.advance);
        $('#eid').val(farmer.id);
        $('#editModal').modal('show');

    }

    var lock=false;
    function saveData(e) {
        e.preventDefault();
        if(lock){

        }else{
            if($('#center_id').val()==-1){
                alert('Please select Collection center ');
                return;
            }
            if(!($('#auto')[0].checked ) && $('#farmer_no').val()=='') {
                alert("Please enter farmer no");
                return;
            }
        lock=true;
        var center = $('#center_id').val();
        var bodyFormData = new FormData(document.getElementById('form_validation'));
        axios({
                method: 'post',
                url: '{{ route("admin.farmer.add")}}',
                data: bodyFormData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                console.log(response);
                showNotification('bg-success', 'Farmer added successfully!');
                if(!(document.getElementById('another').checked)){
                    $('#largeModal').modal('toggle');
                }
                $('#form_validation').trigger("reset");
                $('#name').val('');
                $('#farmer_no').val('');
                $('#advance').val(0);
                $('#phone').val('');
                $('#address').val('');
                $('#ts_amount').val('');
                $('#use_ts_amount')[0].checked=false;
                if($('#center_id').val()==$('#loadFarmerByCenter').val()){
                    $('#farmerData').prepend(response.data);
                }

                if($('#auto')[0].checked){
                    $('#name').focus();

                }else{
                    $('#farmer_no').focus();
                }
                $('#center_id').val(center).change();
                lock=false;
            })
            .catch(function(response) {
                //handle error
                console.log(response);
                lock=false;
            });

        }
    }

    function changeAutoIncrement(ele){
        if(ele.checked){
            $('#farmer_no').removeAttr('required');
        }else{
            $('#farmer_no').attr('required', 'required');
        }
    }

    // edit data
    function editData(e) {
        e.preventDefault();
        var rowid = $('#eid').val();
        var bodyFormData = new FormData(document.getElementById('editform'));
        axios({
                method: 'post',
                url: '{{route('admin.farmer.update')}}',
                data: bodyFormData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                console.log(response);
                showNotification('bg-success', 'Updated successfully!');
                $('#editModal').modal('toggle');
                $('#farmer-' + rowid).replaceWith(response.data);
            })
            .catch(function(response) {
                //handle error
                console.log(response);
                showNotification('bg-danger', 'You hove no authority!');

            });
    }


    // delete
    function removeData(id) {
        var dataid = id;
        if (confirm('Are you sure?')) {
            axios({
                    method: 'get',
                    url: '/admin/farmers/delete/' + dataid,
                })
                .then(function(response) {
                    // console.log(response.data);
                    $('#farmer-' + dataid).remove();
                    showNotification('bg-danger', 'Deleted Successfully !');
                })
                .catch(function(response) {
                    //handle error
                    showNotification('bg-danger','You do not have authority to delete!');
                    console.log(response);
                });
        }
    }

    $("#loadFarmerByCenter").change(function () {
        var center_id = $('#loadFarmerByCenter').val();
        axios({
            method: 'post',
            url: '{{ route("admin.farmer.list-bycenter")}}',
            data:{'center':center_id}
        })
        .then(function(response) {
            // console.log(response.data);
            $('#farmerData').empty();
            $('#farmerData').html(response.data);
            initTableSearch('sid', 'farmerData', ['name','no']);
        })
        .catch(function(response) {
            //handle error
            console.log(response);
        });
    });


    $(document).bind('keydown', 'alt+n', function(e){
       $('#largeModal').modal('show');
    });

    $('#auto').change(function(){
        if((document.getElementById('auto').checked)){
            $('#no').attr('disabled', 'disabled');
        }else{
            $('#no').removeAttr('disabled');

        }
    })

</script>
@endsection
