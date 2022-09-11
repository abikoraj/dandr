<div class="container">
    <form action="{{route('admin.accounting.accounts.fixed.assets.update',['id'=>$id])}}" method="post" id="updateFixedAssetForm" onsubmit="return updateFixedAsset(event,this,{{$fixedAsset->id}});">
       @csrf
        <div class="row">
            <div class="mb-2 col-md-4">
                <label for="fixed_asset_category_id">Category</label>
                <select name="fixed_asset_category_id" id="fixed_asset_category_id" class="form-control ms">
                    @foreach ($cats as $cat)
                        <option value="{{$cat->id}}" {{$cat->id==$fixedAsset->fixed_asset_category_id?"selected":""}}>{{$cat->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2 col-md-8">
                <label for="name">Name</label>
                <input type="text" name="name" id="ename" class="form-control" required value="{{$fixedAsset->name}}">
            </div>
            <div class="col-md-3 mb-2">
                <label for="depreciation_type">Depreciation Type</label>
                <select name="depreciation_type" id="edepreciation_type" class="form-control ms">
                    <option value="0" {{$fixedAsset->depreciation_type==0?'selected':''}}>No Depreciation</option>
                    <option value="1"  {{$fixedAsset->depreciation_type==1?'selected':''}}>Straight Line Method</option>
                    <option value="2"  {{$fixedAsset->depreciation_type==2?'selected':''}}>Declining Balance Method</option>
                </select>
            </div>
            <div class="mb-2 col-md-3">
                <label for="depreciation">Depreciation %</label>
                <input type="number" step="0.01" min="0" max="100" name="depreciation" id="edepreciation" class="form-control" required value="{{$fixedAsset->depreciation}}">
            </div>
            <div class="col-md-3 mb-2">
                <label for="appreciation_type">Appreciation Type</label>
                <select name="appreciation_type" id="eappreciation_type" class="form-control ms">
                    <option value="0"  {{$fixedAsset->appreciation_type==0?'selected':''}} >No Appreciation</option>
                    <option value="1" {{$fixedAsset->appreciation_type==1?'selected':''}} >Straight Line Method</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label for="appreciation">Appreciation %</label>
                <input type="number" step="0.01" min="0" max="100" name="appreciation" id="eappreciation" class="form-control" value="{{$fixedAsset->appreciation}}" >
            </div>
            <div class="mb-2 col-md-3">
                <label for="startdate">Depreciation Start </label>
                <input type="text" id="estartdate" name="startdate" value="{{_nepalidate($fixedAsset->startdate)}}" class="form-control " required value="{{$fixedAsset->startdate}}">
            </div>
            <div class="mb-2 col-md-3">
                <label for="amount">Current Value </label>
                <input type="number" step="0.01" min="0"  name="amount" id="eamount" class="form-control" required value="{{$fixedAsset->amount}}">
            </div>
            <div class="mb-2 col-md-3">
                <label for="amount">Original Value </label>
                <input type="number" step="0.01" min="0"  name="full_amount" id="efull_amount" class="form-control" required value="{{$fixedAsset->full_amount}}">
            </div>
            <div class="col-md-3 mb-2">
                <label for="salvage_amount">Scrap Value </label>
                <input type="number" step="0.01" min="0"  name="salvage_amount" id="esalvage_amount" class="form-control" required value="{{$fixedAsset->salvage_amount}}">
            </div>
            <div class="mb-2 col-md-5">
                <div class="row">
                </div>
            </div>
            <div class="col-12 pb-3">
                <button class="btn btn-primary">Update Asset</button>
            </div>
        </div>
    </form>
</div>
