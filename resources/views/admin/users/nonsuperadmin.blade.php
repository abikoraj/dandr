@extends('admin.layouts.app')
@section('title','Change Password')
@section('head-title','Users Change Password')

@section('content')

<div class="table-responsive">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
    @if(session()->has('message_danger'))
        <div class="alert alert-danger">
            {{ session()->get('message_danger') }}
        </div>
    @endif
<form action="{{ route('user.non.super.admin.change.password',$user->id) }}" method="POST" onsubmit="return checkConfirm(event);">
    @csrf
    <div class="row">
        <div class="col-lg-10">
            <label for="cprice">New Password</label>
            <div class="form-group">
                <input type="password" name="n_password" id="npass" class="form-control"  placeholder="Enter Password" required>
            </div>
        </div>

        <div class="col-lg-10">
            <label for="cprice">Confirm Password</label>
            <div class="form-group">
                <input type="password" id="cpass" class="form-control"  placeholder="Enter Password" required>
            </div>
        </div>
        <div class="col-md-4">
            <button class="btn btn-primary">Save Change</button>
        </div>
    </div>

</form>
</div>




@endsection
@section('js')
<script>
    function checkConfirm(e){
        var p1=$('#npass').val();
        var p2=$('#cpass').val();
        if(p1 != p2){
            e.preventDefault();
            alert('Your new password doesnt match !');
        }
    }
</script>
@endsection
