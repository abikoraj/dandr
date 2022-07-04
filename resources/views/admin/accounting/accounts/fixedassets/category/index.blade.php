@extends('admin.layouts.app')
@section('title')
    Fixed Assets  / Categories
@endsection
@section('head-title')
<a href="{{route('admin.accounting.index')}}">Accounting</a>
/ <a href="{{route('admin.accounting.accounts.index')}}">Accounts</a>
/ Fixed Assets  / Categories
@endsection
@section('content')

    <div class="shadow p-2">
        <form action="{{route('admin.accounting.accounts.fixed.assets.categories.add')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control">
                </div>
                <div class="col-md-4">
                    <label label="depreciation">Depreciation</label>
                    <input type="number" min="0" max="100" step="0.01" name="depreciation" id="depreciation" class="form-control">
                </div>
                <div class="col-md-4 pt-4"><button class="btn btn-primary">Add New Category</button></div>
            </div>
        </form>
    </div>
    <hr>
    <div class="row">
        @foreach ($cats as $cat)
            @include('admin.accounting.accounts.fixedassets.category.single',['cat'=>$cat])
        @endforeach
    </div>
@endsection
@section('js')
    <script>
        function del(id) {
            if(prompt('Enter yes to delete category')=='yes'){
                showProgress('Deleting Category');
                axios.post('{{route('admin.accounting.accounts.fixed.assets.categories.del')}}',{id:id})
                .then((res)=>{
                    $('#cat-'+id).remove();
                    showNotification("bg-success","Category deleted sucessfully.");
                    hideProgress();
                })
                .catch((err)=>{
                    showNotification("bg-danger","Cannot delete category");
                    hideProgress();

                })
            }
        }
    </script>

@endsection
