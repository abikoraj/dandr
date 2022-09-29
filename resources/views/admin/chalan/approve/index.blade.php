@extends('admin.layouts.app')
@section('title', 'Employee Chalan - closing')
@section('head-title')
    <a href="{{ route('admin.chalan.index') }}">Employee Chalans</a>
    / {{ $chalan->name }} / {{ _nepalidate($chalan->date) }} / approve
@endsection
@section('css')
@endsection
@section('content')
    <div class="shadow p-3 mb-3">
        <div class="row">
            <div class="col-4">
                <strong>Issued to</strong>
                <div>
                    {{ $chalan->name }}
                </div>
            </div>
            <div class="col-4">
                <strong>Date</strong>
                <div>
                    {{ _nepalidate($chalan->date) }}
                </div>
            </div>

        </div>
    </div>
    <div class="shadow p-3 mb-3">
        <table class="table">
            <tr>
                <th>Item</th>
                <th>Rate</th>
                <th>Qty</th>
            </tr>
            @foreach ($items as $item)
            <tr>
                <th>{{$item->title}}</th>
                <th>{{$item->rate}}</th>
                <th>{{$item->qty}}</th>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="shadow p-3 mb-3">
        <div class="row">
            <div class="col-md-3">
                @if (auth_has_per('15.08'))
                    <a href="{{ route('admin.chalan.manage.edit',$chalan->id) }}" class="btn btn-success w-100">
                        Edit
                    </a>
                @endif

            </div>
            <div class="col-md-3">
                <form action="{{route('admin.chalan.manage.approve',['id'=>$chalan->id])}}" method="post">
                    @csrf
                    <button class="btn btn-primary w-100" onclick="return prompt('Enter yes to continue')=='yes';">Approve</button>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('js')
@endsection
