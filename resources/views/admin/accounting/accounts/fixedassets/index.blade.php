 @extends('admin.layouts.app')
@section('title')
    Fixed Assets ( {{$account->fiscalyear->name}} ) / Accounts
@endsection
@section('head-title')
<a href="{{route('admin.accounting.index')}}">Accounting</a>
/ <a href="{{route('admin.accounting.accounts.index')}}">Accounts</a>
/ Fixed Assets ( {{$account->fiscalyear->name}} ) / Accounts
@endsection
@section('toobar')
    <a href="{{route('admin.accounting.accounts.fixed.assets.categories.index')}}" target="_blank" class="btn btn-primary">Manage Categories</a>
@endsection
@section('content')
    <div class="shadow mb-3 p-2">
        <form action="{{route('admin.accounting.accounts.fixed.assets.add')}}" method="post" id="addFixedAssetForm">
           @csrf
            <div class="row">
                <div class="col-md-4 mb-2">
                    <label for="fixed_asset_category_id">Category</label>
                    <select name="fixed_asset_category_id" id="fixed_asset_category_id" class="form-control ms">
                        @foreach ($cats as $cat)
                            <option value="{{$cat->id}}">{{$cat->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8 mb-2">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="col-md-3 mb-2">
                    <label for="depreciation_type">Depreciation Type</label>
                    <select name="depreciation_type" id="depreciation_type" class="form-control ms">
                        <option value="0">No Depreciation</option>
                        <option value="1">Straight Line Method</option>
                        <option value="2">Declining Balance Method</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label for="depreciation">Depreciation %</label>
                    <input type="number" step="0.01" min="0" max="100" name="depreciation" id="depreciation" class="form-control" >
                </div>
                <div class="col-md-3 mb-2">
                    <label for="appreciation_type">Appreciation Type</label>
                    <select name="appreciation_type" id="appreciation_type" class="form-control ms">
                        <option value="0">No Appreciation</option>
                        <option value="1">Straight Line Method</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label for="appreciation">Appreciation %</label>
                    <input type="number" step="0.01" min="0" max="100" name="appreciation" id="appreciation" class="form-control" >
                </div>
              
                <div class="col-md-3 mb-2">
                    <label for="startdate">Depreciation Start </label>
                    <input type="text" id="startdate" name="startdate" value="{{_nepalidate($account->fiscalYear->startdate)}}" class="form-control calender" required>
                </div>
                <div class="col-md-3 mb-2">
                    <label for="amount">Current Value </label>
                    <input type="number" step="0.01" min="0"  name="amount" id="amount" class="form-control" required>
                </div>
                <div class="col-md-3 mb-2">
                    <label for="full_amount">Purchase Value </label>
                    <input type="number" step="0.01" min="0"  name="full_amount" id="full_amount" class="form-control" required>
                </div>
                <div class="col-md-3 mb-2">
                    <label for="salvage_amount">Scrap Value </label>
                    <input type="number" step="0.01" min="0"  name="salvage_amount" id="salvage_amount" class="form-control" required>
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
                    Category
                </th>
                <th>
                    Name
                </th>
                <th>
                    Purchase Value
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
                <th>
                    Salvage Value
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
