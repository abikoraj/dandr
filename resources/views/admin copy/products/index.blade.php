@extends('admin.layouts.app')
@section('title','Products')
@section('head-title','Products')
@section('toobar')
<button type="button" class="btn btn-primary waves-effect m-r-20" data-toggle="modal" data-target="#largeModal">New Product</button>
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
                <th>Name</th>
                <th>Rate</th>
                <th>unit</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="data">
            @foreach($products as $product)
                @include('admin.products.single',['product'=>$product])
            @endforeach

        </tbody>
    </table>
</div>


<!-- modal -->

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" data-ff="name">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Create New Collection Centers</h4>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                    <form id="form_validation" method="POST" onsubmit="return saveData(event);">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="name">Product Name</label>
                                <div class="form-group">
                                    <input type="text" id="name" name="name" class="form-control next" data-next="price" placeholder="Product Name" required>
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <label for="name">Rate</label>
                                <div class="form-group">
                                    <input type="number" id="price" name="price" class="form-control next" data-next="unit" step="0.001" placeholder="Enter rate" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="name">Unit (EG. KG)</label>
                                <div class="form-group">
                                    <input type="text" id="unit" name="unit" class="form-control next" data-next="stock" placeholder="Product Name" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="name">Stock (EG. KG)</label>
                                <div class="form-group">
                                    <input type="text" id="stock" name="stock" class="form-control next" data-next="address" placeholder="Enter Stock" required>
                                </div>
                            </div>


                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-raised btn-primary waves-effect" type="submit">Submit Data</button>
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>


@endsection
@section('js')
<script>
    initTableSearch('sid', 'farmerData', ['name']);

    function saveData(e) {
        e.preventDefault();
        var bodyFormData = new FormData(document.getElementById('form_validation'));
        axios({
                method: 'post',
                url: '{{ route("product.add")}}',
                data: bodyFormData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                // console.log(response);
                showNotification('bg-success', 'Collection center added successfully!');
                $('#largeModal').modal('toggle');
                $('#form_validation').trigger("reset")
                $('#data').prepend(response.data);
            })
            .catch(function(response) {
                //handle error
                console.log(response);
            });
    }

    // edit data
    function update(e) {
        var bodyFormData = new FormData(document.getElementById('collectionForm-' + e));
        // console.log(bodyFormData);
        axios({
                method: 'post',
                url: '{{route('product.update')}}',
                data: bodyFormData,
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(function(response) {
                // console.log(response);
                showNotification('bg-success', 'Updated successfully!');
            })
            .catch(function(response) {
                //handle error
                showNotification('bg-danger', 'You do not have authority to update !');
                console.log(response);
            });
    }


    function del(id) {
        var dataid = id;
        if (confirm('Are you sure?')) {
            axios({
                    method: 'post',
                    url: '{{route('product.del')}}',
                    data:{'id':id}
                })
                .then(function(response) {
                    // console.log(response.data);
                    $('#center-' + dataid).remove();
                    showNotification('bg-danger', 'Deleted Successfully !');
                })
                .catch(function(response) {
                    //handle error
                    showNotification('bg-danger', 'You do not have authority to delete !');
                    console.log(response);
                });
        }
    }
</script>
@endsection
