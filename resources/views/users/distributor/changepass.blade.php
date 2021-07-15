@extends('users.distributor.layout.app')
@section('title','Change Passwored')
@section('head-title')
{{ Auth::user()->name }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-8">
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
        <h5>Change Password</h5>
        <hr>
        <form action="{{ route('distributer.change.password') }}" method="POST" onsubmit="return checkPass(event);">
            @csrf
            <div class="form-group">
                <label for="Current">Current Password</label>
                <input type="password" name="c_pass" class="form-control" placeholder="Enter Current Password" required>
            </div>
            <div class="form-group">
                <label for="Current">New Password</label>
                <input type="password" name="n_pass" id="pass" class="form-control" placeholder="Enter New Password" required>
            </div>

            <div class="form-group">
                <label for="Current">Confirm Password</label>
                <input type="password" id="pass1" class="form-control" placeholder="Confirm Password" required>
            </div>
            <div class="form-group">
               <button class="btn btn-primary btn-block">Save Change</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('js')
 <script>
     function checkPass(e){
         var p1 = $('#pass').val();
         var p2 = $('#pass1').val();
         if(p1 != p2){
            e.preventDefault();
            alert('New password does not match !');
         }
     }
 </script>
@endsection
