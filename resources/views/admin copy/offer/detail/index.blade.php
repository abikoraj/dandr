@extends('admin.layouts.app')
@section('title')
    Offer - {{ $offer->name }}
@endsection
@section('head-title')
    <a href="{{ route('admin.offers.index') }}">Offers </a> /
    {{ $offer->name }}
@endsection
@section('toobar')

@endsection
@section('content')
    @include('admin.offer.header')

    @include('admin.offer.single',['offer'=>$offer])
    <hr>    
    @include('admin.offer.detail.add',['offer'=>$offer]);
    

@endsection
@section('js')

@endsection
