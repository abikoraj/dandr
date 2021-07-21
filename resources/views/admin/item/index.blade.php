@extends('admin.layouts.app')
@section('title','Items')
@section('head-title','Items')
@section('toobar')
<button type="button" class="btn btn-primary waves-effect m-r-20" data-toggle="modal" data-target="#largeModal">Create New Item</button>
@endsection
@section('content')
<div class="pt-2 pb-2">
    <input type="text" id="sid" placeholder="Search">
</div>
<div class="table-responsive">
    <table id="newstable1" class="table table-bordered table-striped table-hover js-basic-example dataTable">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Item Number</th>
                <th>Cost Price</th>
                <th>sell Price </th>
                <th>Stock </th>
                <th>Unit Type</th>
                <th>Reward (%)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="itemData">
            @foreach($items as $item)
                @include('admin.item.single',['item'=>$item])
            @endforeach
        </tbody>
    </table>
</div>
@include('admin.item.add')
{{-- @include('admin.item.edit') --}}
@endsection
@section('js')
<script>
    initTableSearch('sid', 'itemData', ['name']);
    lock=false;
    function saveData(e) {
        e.preventDefault();
        if(!lock){
            lock=true;
        var bodyFormData = new FormData(document.getElementById('add-bill'));
        axios({
                method: 'post',
                url: '{{ route("admin.item.save")}}',
                data: bodyFormData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                console.log(response);
                showNotification('bg-success', 'Item added successfully!');
                $('#largeModal').modal('toggle');
                $('#form_validation').trigger("reset")
                $('#itemData').prepend(response.data);
                lock=false;
            })
            .catch(function(response) {
                showNotification('bg-danger','Item Number already exist!');
                //handle error
                console.log(response);
                lock=false;

            });
        }
    }

    function initEdit(id) {
        win.showPost("Edit Item","{{route('admin.item.edit')}}",{"id":id});
    }

    function editData(e) {

        e.preventDefault();
        if(!lock){
            lock=true;
        var trid = $('#eid').val();
        var dataBody = new FormData(document.getElementById('editform'));
        axios({
                method: 'post',
                url: '{{route('admin.item.update')}}',
                data: dataBody,
            })
            .then(function(response) {
                showNotification('bg-success', 'Item updated successfully!');
                win.hide();
                $('#item-' + trid).replaceWith(response.data);
                lock=false;

            })
            .catch(function(response) {
                console.log(response);    
                lock=false;

            });
        }
    }

    // delete item
    function removeData(id) {
        if (confirm('Are you sure?')) {
            axios({
                    method: 'get',
                    url: '/admin/item-delete/' + id,
                })
                .then(function(response) {
                    showNotification('bg-danger', 'Item deleted successfully!');
                    $('#item-' + id).remove();
                })
                .catch(function(response) {
                    showNotification('bg-danger','You do not have authority to delete!');

                    console.log(response)
                })
        }
    }
</script>
@endsection
