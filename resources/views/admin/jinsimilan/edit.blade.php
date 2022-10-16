@extends('admin.layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}">
@endsection
@section('title')
    Jinsi Milan
@endsection
@section('head-title')
    <a href="{{ route('admin.jinsimilan.index') }}">
        Jinsi Minlan
    </a> / {{$jinsiMilan->fromParty}} -> {{$jinsiMilan->toParty}} / Edit
@endsection

@section('content')
    <form action="{{ route('admin.jinsimilan.edit',['id'=>$jinsiMilan->id]) }}" onsubmit="return save(this,event);">
        @csrf
        <div class="row">
            <div class="mb-2 col-md-3">
                <label for="date">Date</label>
                <input type="text" name="date" id="date" class="form-control calender" required value="{{_nepalidate($jinsiMilan->date)}}">
            </div>
           
            <div class="mb-2 col-md-3">
                <label for="amount">Amount</label>
                <input type="number" name="amount" id="amount" class="form-control" step="0.01" required value="{{$jinsiMilan->amount}}">
            </div>
            <div class="mb-2 col-12">
                <label for="detail">detail</label>
                <textarea name="detail" id="detail" class="form-control">{{$jinsiMilan->detail}}</textarea>
            </div>
            <div class="mb-2 col-12">
                <button class="btn btn-primary" onclick="return yes('Enter yes to continue')"> 
                    Save
                </button>
             
            </div>

        </div>
    </form>
@endsection
@section('js')
   
@endsection
