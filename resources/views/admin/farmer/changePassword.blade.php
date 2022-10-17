@extends('admin.layouts.app')
@section('title', 'Farmer Change Password')
@section('head-title')
    <a href="{{ route('admin.farmer.list') }}">Farmers</a> / Change Password
@endsection

@section('content')
    <form action="{{ route('admin.farmer.changePassword') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-2">
                <label for="center_id">Center</label>
                <select name="center_id" id="center_id" class="form-control ms">
                    {!! renderCenters(null, true) !!}
                </select>
            </div>
            <div class=" col-md-5">
                <label for="user_id">User</label>
                <select name="user_id" id="user_id" class="form-control ms" required>
                    {{-- @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach --}}
                </select>
            </div>
            <div class=" col-md-3">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" minlength="6" required>
            </div>
            <div class="col-md-2 d-flex align-items-end ">
                <button class="btn btn-primary w-100">
                    Save
                </button>
            </div>
        </div>
    </form>

@endsection
@section('js')
    @include('admin.layouts.select2')
    <script>
        const users = {!! json_encode($users) !!}
        $(document).ready(function() {
            $("#user_id").select2();

            $('#center_id').change(function(e) {
                e.preventDefault();
                
                let html = "<option></option>";
                const selectedUser=users.filter(o=>o.center_id==this.value);
                html+=(selectedUser.map(o=>`<option value="${o.id}">${o.name} - ${o.phone}</option>`));
                $('#user_id').html(html);
                $("user_id").select2("destroy").select2();

            });
        });
    </script>
@endsection
