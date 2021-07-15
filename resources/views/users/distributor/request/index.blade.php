@extends('users.distributor.layout.app')
@section('title','Distributor Request')
@section('css')
<link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
@endsection
@section('toobar')
<button type="button" class="btn btn-primary waves-effect m-r-20" data-toggle="modal" data-target="#largeModal">Create New Request</button>
@endsection
@section('head-title')
Make a Request
@endsection
@section('content')
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
<div class="table-responsive">
    <table id="newstable1" class="table table-bordered table-striped table-hover js-basic-example dataTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Item Name</th>
                <th>Required Qty (Kg/Ltr)</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="itemData">
            @foreach (App\Models\Distributerreq::latest()->get() as $item)
                <tr>
                    <td>{{ _nepalidate($item->date) }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->amount }}</td>
                    <td><span class="badge badge-{{ $item->status==0?'primary':'success'}}">{{ $item->status==0?'Pending':'Received'}}</span></td>
                    @if ($item->status==0)
                        <td>
                            <button  type="button" data-edit="{{$item->toJson()}}" class="btn btn-primary btn-sm editrequest" onclick="initEdit(this);" >Edit</button>
                            <a href="{{ route('distributer.request.delete',$item->id)}}" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure ?');">Del</a>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>




<!-- modal -->

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" data-ff="iname">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Create New Item</h4>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                    <form id="form_validation" action="{{ route('distributer.request.add') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="name">Date</label>
                                <div class="form-group">
                                    <input type="text" name="date" id="nepali-datepicker" class="form-control" data-next="title" placeholder="Date" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Items</label>
                                    <select name="item" id="yr" class="form-control show-tick ms select2" required>
                                        <option></option>
                                        @foreach (App\Models\Product::all() as $item)
                                          <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="cprice">Required Qty</label>
                                    <input type="number" min="1" name="amount" placeholder="Enter required amount" value="1" class="form-control" required>
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

<!--Edit modal -->

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-ff="iname">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Create New Item</h4>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                    <form id="form_validation" action="{{ route('distributer.request.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <input type="hidden" id="eid" name="id" >
                                <label for="name">Date</label>
                                <div class="form-group">
                                    <input type="text" name="date" id="enepali-datepicker" class="form-control" data-next="title" placeholder="Date" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name">Items</label>
                                    <select name="item" id="eitem" class="form-control show-tick ms select2" required>
                                        <option></option>
                                        @foreach (App\Models\Product::all() as $item)
                                          <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="cprice">Required Qty</label>
                                    <input type="number" min="1" id="eqty" name="amount" placeholder="Enter required amount" value="1" class="form-control" required>
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
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('calender/nepali.datepicker.v3.2.min.js') }}"></script>
<script>
    function initEdit(ele){
        var req = JSON.parse(ele.dataset.edit);
        $('#eid').val(req.id);
        $('#enepali-datepicker').val(req.date);
        $('#eitem').val(req.item_name).change();
        $('#eqty').val(req.amount);
        $('#editModal').modal('show');
    }
    window.onload = function() {
        var mainInput = document.getElementById("nepali-datepicker");
        mainInput.nepaliDatePicker();
        var editdate = document.getElementById("enepali-datepicker");
        editdate.nepaliDatePicker();
    };
</script>
@endsection
