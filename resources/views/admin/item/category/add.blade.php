<div class="p-3">
    <form action="{{ route('admin.item.categories.add', ['id' => $id]) }}" onsubmit="save(this,event)">
        @csrf
        <div class="row justify-content-center">
            <div class="col-md-3">
                <label for="name">Category Name</label>
                <input type="text" name="name" id="name" required class="form-control">
            </div>
            <div class="col-md-3">
                <label for="price">Category Rate</label>
                <input type="number" min="0" name="price" required id="price" class="form-control">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary">
                    Save Category
                </button>
            </div>
        </div>
    </form>

</div>
