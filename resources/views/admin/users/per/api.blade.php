@extends('admin.layouts.app')
@section('title','User Permission')
@section('head-title')
<a href="{{route('admin.user.users')}}">
    Users
</a>
/API Permission
@endsection
@section('content')
    <form action="{{route('admin.user.api.per',['user'=>$user->id])}}" method="post">
    @csrf
    <div class="row">
        @foreach ($centers as $center)
            <div class="col-md-4">
                <input type="checkbox" name="centers[]" {{in_array($center->id,$data->centers)?'checked':''}} value="{{$center->id}}" id="center-{{$center->id}}" class="mr-2">
                <label for="center-{{$center->id}}">
                    {{$center->name}}
                </label>

            </div>

        @endforeach
    </div>
    <div class="pt-2">
        <button class="btn btn-primary">
            Save Permissions
        </button>
    </div>
    </form>

@endsection
@section('js')
    <script>
        function selectAll(){
            $('.check').each(function (index, element) {
                element.checked=true;

            });
        }
        function select(key){
            $('.check-'+key).each(function (index, element) {
                element.checked=true;

            });
        }
        function clearAll(){
            $('.check').each(function (index, element) {
                element.checked=false;

            });
        }
        function clearSel(key){
            console.log('.check-'+key);
            $('.check-'+key).each(function (index, element) {
                element.checked=false;

            });
        }
    </script>
@endsection
