@extends('admin.layouts.app')
@section('title')
    Fixed Assets ( {{$account->fiscalyear->name}} ) / Accounts
@endsection
@section('head-title')
<a href="{{route('admin.accounting.index')}}">Accounting</a>
/ <a href="{{route('admin.accounting.accounts.index')}}">Accounts</a>
/ Fixed Assets ( {{$account->fiscalyear->name}} ) / Accounts
@endsection
@section('content')
    <div class="shadow mb-3 p-2">
        <form action="{{route('admin.accounting.accounts.fixed.assets.add')}}" method="post" id="addFixedAssetForm">
           @csrf
            <div class="row">
                <div class="col-md-3">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label for="depreciation">Depreciation %</label>
                    <input type="number" step="0.01" min="0" max="100" name="depreciation" id="depreciation" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <label for="startdate">Depreciation Start </label>
                    <input type="text" id="startdate" name="startdate" value="{{_nepalidate($account->fiscalYear->startdate)}}" class="form-control calender" required>
                </div>
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-6">
                            <label for="amount">Current Value </label>
                            <input type="number" step="0.01" min="0"  name="amount" id="amount" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label for="amount">Original Value </label>
                            <input type="number" step="0.01" min="0"  name="full_amount" id="full_amount" class="form-control" required>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="account_id" value="{{$account->id}}">
                <div class="col-12 pb-3">
                    <button class="btn btn-primary">Add New Asset</button>
                </div>
            </div>
        </form>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>
                    Name
                </th>
                <th>
                    Original Value
                </th>
                <th>
                    Current Value
                </th>
                <th>
                    Depreciation Start Date
                </th>
                <th>
                    Depreciation %
                </th>
                <th></th>
            </tr>
        </thead>
        <tbody id="data">
            @foreach ($fixedAssets as $fixedAsset)
               @include('admin.accounting.accounts.fixedassets.single',['fixedAsset'=>$fixedAsset])
            @endforeach
        </tbody>
    </table>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $('#addFixedAssetForm').submit(function (e) {
                e.preventDefault();
                if(prompt('Enter yes to continue')=="yes"){
                    showProgress("Adding asset");
                    axios.post(this.action,new FormData(this))
                    .then((res)=>{
                        this.reset();
                        $('#data').append(res.data);
                        hideProgress();
                        showNotification('bg-success','Asset added sucessfully');
                    })
                    .catch((err)=>{
                        hideProgress();
                        showNotification('bg-danger','Error : '+err.response.data.message);
                    });
                }
            });
        });
        function initUpdateFixedAsset(id){
            const updateURL="{{route('admin.accounting.accounts.fixed.assets.update',['id'=>'xxx_id'])}}";
            win.showGet("Update Fixed Asset",updateURL.replace('xxx_id',id));
        }

        function updateFixedAsset(e,ele,id){
            e.preventDefault();
            showProgress("Updating Asset")
            axios.post(ele.action,new FormData(ele))
            .then((res)=>{
                hideProgress();
                showNotification('bg-success','Asset updated successfully');
                $('#fixedAsset-'+id).replaceWith(res.data);
                win.hide();
            })
            .catch((err)=>{
                hideProgress();
                if(err.response){

                    showNotification('bg-danger','Error : '+err.response.data.message);
                }else{
                    showNotification('bg-danger','Error : '+err);

                }
            })

        }

        function initDel(id){
            if(prompt('Enter yes to delete')=='yes'){
                showProgress("Deleting Asset")
                axios.post("{{route('admin.accounting.accounts.fixed.assets.del')}}",{id:id})
                .then((res)=>{
                    $('#fixedAsset-'+id).remove();
                    showNotification('bg-success','Asset deleted successfully');
                    hideProgress();
                })
                .catch((err)=>{
                    showNotification('bg-danger','Error deleting asset');

                });
            }
        }
    </script>

@endsection
