<div class="container">
    <form action="{{route('admin.accounting.accounts.fixed.assets.update',['id'=>$id])}}" method="post" id="updateFixedAssetForm" onsubmit="return updateFixedAsset(event,this,{{$fixedAsset->id}});">
       @csrf
        <div class="row">
            <div class="col-md-3">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" required value="{{$fixedAsset->name}}">
            </div>
            <div class="col-md-2">
                <label for="depreciation">Depreciation %</label>
                <input type="number" step="0.01" min="0" max="100" name="depreciation" id="depreciation" class="form-control" required value="{{$fixedAsset->depreciation}}">
            </div>
            <div class="col-md-2">
                <label for="startdate">Depreciation Start </label>
                <input type="text" id="startdate" name="startdate" value="{{_nepalidate($fixedAsset->startdate)}}" class="form-control " required value="{{$fixedAsset->startdate}}">
            </div>
            <div class="col-md-5">
                <div class="row">
                    <div class="col-6">
                        <label for="amount">Current Value </label>
                        <input type="number" step="0.01" min="0"  name="amount" id="amount" class="form-control" required value="{{$fixedAsset->amount}}">
                    </div>
                    <div class="col-6">
                        <label for="amount">Original Value </label>
                        <input type="number" step="0.01" min="0"  name="full_amount" id="full_amount" class="form-control" required value="{{$fixedAsset->full_amount}}">
                    </div>
                </div>
            </div>
            <div class="col-12 pb-3">
                <button class="btn btn-primary">Update Asset</button>
            </div>
        </div>
    </form>
</div>
