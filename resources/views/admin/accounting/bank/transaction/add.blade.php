@extends('admin.layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="type">Transaction Type</div>
            <select name="type" id="type">
                <option value="1">Cash withdrawl from bank</option>
                <option value="1">Cash deposit to bank</option>
                <option value="1">Bank Transfer</option>
            </select>
        </div>
    </div>
@endsection