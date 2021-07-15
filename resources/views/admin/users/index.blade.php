@extends('admin.layouts.app')
@section('title','Admin User')
@section('head-title','Users')
@section('toobar')
<button type="button" class="btn btn-primary waves-effect m-r-20" data-toggle="modal" data-target="#largeModal">Create New Users</button>
@endsection
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

    <table id="newstable1" class="table table-bordered table-striped table-hover js-basic-example dataTable">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Login User Number</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach (\App\Models\User::where('role',0)->get() as $user)
                <tr>
                    <form action="{{ route('user.update',$user->id) }}" method="POST">
                        @csrf
                        <td><input type="text" name="name" value="{{ $user->name }}" class="form-control"> </td>
                        <td><input type="text" name="phone" value="{{ $user->phone }}" class="form-control" readonly></td>
                        <td><input type="text" name="address" value="{{ $user->address }}" class="form-control"></td>
                        <td>
                            @if ($user->phone != env("authphone",''))
                                <button class="badge badge-primary"> Update </button> |
                                <a href="{{ route('user.delete',$user->id) }}" onclick="return confirm('Are you sure?');" class="badge badge-danger">Delete</a> |
                                <a href="{{ route('user.non.super.admin.change.password',$user->id) }}" class="btn btn-primary btn-sm">Change Password</a>
                            @else
                                <button type="button" class="btn btn-primary btn-sm waves-effect m-r-20"  data-toggle="modal" onclick="getUserId({{$user->id}});" data-target="#ChangePass">Change Password</button>
                            @endif
                        </td>
                    </form>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- modal -->

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" data-ff="iname">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Create New User</h4>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                    <form id="form_validation" action="{{ route('user.add') }}" onsubmit="return checkPass(event);">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="name">User Name</label>
                                <div class="form-group">
                                    <input type="text" id="iname" name="name" class="form-control next" data-next="inum" placeholder="Enter item name" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="name">Address</label>
                                <div class="form-group">
                                    <input type="text" name="address" class="form-control next" placeholder="Enter Address" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <label for="name">Login User Number</label>
                                <div class="form-group">
                                    <input type="text" id="inum" name="phone" class="form-control next" data-next="cprice" placeholder="Enter unique item number" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <label for="cprice">Password</label>
                                <div class="form-group">
                                    <input type="password" name="password" id="pass" class="form-control"  placeholder="Enter Password" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <label for="cprice">Confirm Password</label>
                                <div class="form-group">
                                    <input type="password" name="password" id="pass1" class="form-control"  placeholder="Enter Password" required>
                                </div>
                            </div>


                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-raised btn-primary waves-effect" type="submit">Submit Data</button>
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>


<!-- change password modal -->

<div class="modal fade" id="ChangePass" tabindex="-1" role="dialog" data-ff="iname">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Change Password</h4>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                   <form id="validation" action="{{ route('user.change.password') }}" method="POST" onsubmit="return changePassConfirm(event);">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <input type="hidden" id="changeUid" name="id">
                                <label for="cprice">Current Password</label>
                                <div class="form-group">
                                    <input type="password" name="c_password" class="form-control"  placeholder="Enter Current Password" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <label for="cprice">Password</label>
                                <div class="form-group">
                                    <input type="password" name="n_password" id="npass" class="form-control"  placeholder="Enter Password" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <label for="cprice">Confirm Password</label>
                                <div class="form-group">
                                    <input type="password" id="cpass" class="form-control"  placeholder="Enter Password" required>
                                </div>
                            </div>

                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-raised btn-primary waves-effect" type="submit">Submit Data</button>
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>


@endsection
@section('js')
<script>
 function getUserId(ele){
    var id = ele;
    $('#changeUid').val(id);
    // console.log(id);
 }

 function checkPass(e){
     var p1 = $('#pass').val();
     var p2 = $('#pass1').val();
     if(p1 != p2){
        e.preventDefault();
         alert('Your password doesnt match !');
     }
 }

 function changePassConfirm(e){
    var p1 = $('#npass').val();
    var p2 = $('#cpass').val();
    if(p1 != p2){
        e.preventDefault();
         alert('Your new password doesnt match !');
     }
 }

</script>
@endsection
