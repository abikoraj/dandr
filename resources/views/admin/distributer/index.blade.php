@extends('admin.layouts.app')
@section('title','Distributers')
@section('head-title','Distributers')
@section('toobar')
@if (auth_has_per('04.02'))
<button type="button" class="btn btn-primary waves-effect m-r-20" data-toggle="modal" data-target="#largeModal">Create Distributer</button>
@endif
@endsection
@section('content')
<div class="pt-2 pb-2">
    <input type="text" id="sid" placeholder="Search">
</div>
<div class="table-responsive">
    <table id="newstable1" class="table table-bordered table-striped table-hover js-basic-example dataTable">
        <thead>
            <tr>
                <th>#Id</th>
                <th>Distributer Name</th>
                <th>Distributer phone</th>
                <th>Distributer Address</th>
                {{-- <th>Rate</th>
                <th>Amount</th> --}}
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="distributerData">

        </tbody>
    </table>
</div>

@if (auth_has_per('04.02'))

<!-- add modal -->
@include('admin.distributer.add')
@endif
@if (auth_has_per('04.03'))

<!-- edit modal -->
@include('admin.distributer.edit')
@endif


@endsection
@section('js')
<script>
    function initEdit(ele) {
        var distributer = JSON.parse(ele.dataset.distributer);
        console.log(distributer);
        $('#ename').val(distributer.name);
        $('#ephone').val(distributer.phone);
        $('#eaddress').val(distributer.address);
        $('#ecredit_days').val(distributer.credit_days);
        $('#ecredit_limit').val(distributer.credit_limit);
        $('#eaddress').val(distributer.address);
        $('#esnf_rate').val(distributer.snf_rate);
        $('#efat_rate').val(distributer.fat_rate);
        $('#efat_rate').val(distributer.fat_rate);
        $('#eadded_rate').val(distributer.added_rate);
        if(distributer.is_fixed==1){
            $('#eis_fixed')[0].checked=true;
        }else{
            $('#eis_fixed')[0].checked=false;
        }
        $('#efixed_rate').val(distributer.fixed_rate);
        // $('#erate').val(ele.dataset.rate);
        // $('#eamt').val(ele.dataset.amount);
        $('#eid').val(distributer.id);
        $('#editModal').modal('show');
    }

    function saveData(e) {
        e.preventDefault();
        var bodyFormData = new FormData(document.getElementById('form_validation'));
        axios({
                method: 'post',
                url: '{{ route("admin.distributer.add")}}',
                data: bodyFormData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                console.log(response);
                showNotification('bg-success', 'Distributer added successfully!');
                $('#largeModal').modal('toggle');
                $('#form_validation').trigger("reset")
                $('#distributerData').prepend(response.data);
            })
            .catch(function(response) {
                //handle error
                console.log(response);
            });
    }

    // edit data
    function editData(e) {
        e.preventDefault();
        var rowid = $('#eid').val();
        var bodyFormData = new FormData(document.getElementById('editform'));
        axios({
                method: 'post',
                url: '{{ route("admin.distributer.update")}}',
                data: bodyFormData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                console.log(response);
                showNotification('bg-success', 'Updated successfully!');
                $('#editModal').modal('toggle');
                $('#distributer-' + rowid).replaceWith(response.data);
            })
            .catch(function(response) {
                //handle error
                console.log(response);
            });
    }

    axios({
            method: 'get',
            url: '{{ route("admin.distributer.list")}}',
        })
        .then(function(response) {
            // console.log(response.data);
            $('#distributerData').html(response.data);
            initTableSearch('sid', 'distributerData', ['name']);
        })
        .catch(function(response) {
            //handle error
            console.log(response);
        });

    // delete
    function removeData(id) {
        var dataid = id;
        if (confirm('Are you sure?')) {
            axios({
                    method: 'get',
                    url: '{{route('admin.distributer.delete')}}',
                    data:{"id":id}
                })
                .then(function(response) {
                    // console.log(response.data);
                    $('#distributer-' + dataid).remove();
                    showNotification('bg-danger', 'Deleted Successfully !');
                })
                .catch(function(response) {
                    //handle error
                    showNotification('bg-danger','You do not have authority to delete!');

                    console.log(response);
                });
        }
    }


</script>
@endsection
