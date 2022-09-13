<div class="p-3 mb-3 shadow" id="category-{{$category->id}}">
    <form action="{{route('admin.item.categories.update',['id'=>$category->id])}}"  method="post" onsubmit="return update(this,event);">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <label for="name">Category Name</label>
                <input type="text" name="name" id="name-{{$category->id}}" class="form-control" value="{{$category->name}}">
            </div>
            <div class="col-md-3">
                <label for="price">Category Rate</label>
                <input type="number" min="0" name="price" id="price-{{$category->id}}" class="form-control" value="{{$category->price}}">
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button class="btn btn-primary">
                    Update Category
                </button>
                <span class="btn btn-danger" onclick="del('{{route('admin.item.categories.del',['id'=>$category->id])}}')">
                    Delete Category
                </span>
            </div>
        </div>
    </form>

</div>
