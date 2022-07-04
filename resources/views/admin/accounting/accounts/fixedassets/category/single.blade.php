<div class="col-md-4" id="cat-{{$cat->id}}">
    <div class="shadow p-2">
        <form action="{{route('admin.accounting.accounts.fixed.assets.categories.update')}}" method="post">
            @csrf
            <input type="hidden" name="id" value="{{$cat->id}}">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="{{$cat->name}}">
            </div>
            <div class="form-group">
                <label label="depreciation">Depreciation</label>
                <input type="number" min="0" max="100" step="0.01" name="depreciation" id="depreciation" class="form-control" value="{{$cat->depreciation}}">
            </div>
            <div class="form-group d-flex justify-content-between">
                <button class="btn btn-primary">Update</button>
                <span class="btn btn-danger" onclick="del({{$cat->id}})">Del</span>
            </div>
        </form>
    </div>
</div>
