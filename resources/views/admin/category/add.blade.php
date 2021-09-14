@extends('admin.layouts.app')
@section('title','Categories')
@section('head-title')
@endsection
@section('toobar')
<button type="button" class="btn btn-primary waves-effect m-r-20" data-toggle="modal" data-target="#largeModal">Create New Center</button>
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
                <th>Center Name</th>
                <th>Center Address</th>
                <th>Fat <br> Rate (Rs.)</th>
                <th>Snf <br> Rate (Rs.)</th>
                @if (env('hasextra',0)==1)
                    <th>
                        Bonus (%)
                    </th>
                @endif
                @if (env('usetc',0)==1)
                    <th>
                        TS
                    </th>
                @endif
                @if (env('usecc',0)==1)
                    <th>
                        Cooling <br>
                        Cost
                    </th>
                @endif
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="farmerData">
            @foreach($centers as $c)
                @include('admin.center.single',['center'=>$c])
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
                                <label for="name">Collection Center Name</label>
                                <div class="form-group">
                                    <input type="text" id="name" name="name" class="form-control next" data-next="address" placeholder="Collection Center Name" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="name">Collection Center Address</label>
                                <div class="form-group">
                                    <input type="text" id="address" name="address" class="form-control next" data-next="fat-rate" placeholder="Collection Center Address" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="name">Fat Rate</label>
                                <div class="form-group">
                                    <input type="number" id="fat-rate" name="fat_rate" class="form-control next" data-next="snf-rate" step="0.001" placeholder="Enter fat rate" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="name">Snf Rate</label>
                                <div class="form-group">
                                    <input type="number" id="snf-rate" name="snf_rate" class="form-control" step="0.001" placeholder="Enter snf rate" required>
                                </div>
                            </div>
                            <div class="col-lg-6 {{env('hasextra',0)==1?"":"d-none"}}" >
                                <label for="name">Bonus (%)</label>
                                <div class="form-group">
                                    <input type="number" id="bonus" name="bonus" class="form-control" step="0.001" placeholder="Enter Bonus" value="0" required>
                                </div>
                            </div>
                            <div class="col-lg-6 {{env('usetc',0)==1?"":"d-none"}}" >
                                <label for="name">TS Commission (%)</label>
                                <div class="form-group">
                                    <input type="number" id="tc" name="tc" class="form-control" step="0.001" placeholder="Enter TC Commission" value="0" required>
                                </div>
                            </div>
                            <div class="col-lg-6 {{env('usecc',0)==1?"":"d-none"}}" >
                                <label for="name">Cooling Cost (%)</label>
                                <div class="form-group">
                                    <input type="number" id="cc" name="cc" class="form-control" step="0.001" placeholder="Enter Cooling Cost" value="0" required>
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
                url: '{{ route("admin.center.add")}}',
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
                $('#farmerData').prepend(response.data);
            })
            .catch(function(response) {
                //handle error
                console.log(response);
            });
    }

    // edit data
    function editCollection(e) {
        var bodyFormData = new FormData(document.getElementById('collectionForm-' + e));
        // console.log(bodyFormData);
        axios({
                method: 'post',
                url: '{{route('admin.center.update')}}',
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

 
    function removeCenter(id) {
        if (confirm('Are you sure?')) {
            axios({
                    method: 'post',
                    url: '{{route('admin.center.delete')}}',
                    data:{"id":id}
                })
                .then(function(response) {
                    // console.log(response.data);
                    $('#center-' + id).remove();
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
