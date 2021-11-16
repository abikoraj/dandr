@extends('admin.layouts.app')
@section('title','User Permission')
@section('head-title','User Permission')
@section('content')
<form action="{{route('admin.user.per',['user'=>$user->id])}}" method="post">
    @csrf
    @foreach ($roles as $key=>$role)
        <div class="p-2 shadow mb-3">
            <h5 class="m-1 cap">
                {{-- <input type="checkbox" id="{{$role['code']}}" name="codes[]" value="{{$role['code']}}" {{has_per($role['code'],$per)?"checked":""}}> --}}
                {{roleToWord($key)}}
            </h5>
            <hr class="m-1">
            @foreach ($role['children'] as $key_child=>$role_child)
               <div class="pl-3">
                   <input type="checkbox" id="{{$role_child['code']}}" name="codes[]" value="{{$role_child['code']}}" {{has_per($role_child['code'],$per)?"checked":""}}>
                   <label for=""></label><strong class="cap">{{roleToWord($key_child)}}</strong>
               </div>
            @endforeach
        </div>
    @endforeach
    <button class="btn btn-primary">
        Submit
    </button>
</form>

@endsection
@section('js')

@endsection
