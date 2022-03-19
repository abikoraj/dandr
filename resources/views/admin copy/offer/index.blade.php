@extends('admin.layouts.app')
@section('title','Offers')
@section('head-title','Offers')
@section('toobar')

@endsection
@section('content')

@include('admin.offer.add')
{{-- @include('admin.customer.edit') --}}
<style>
    .t.active{
        background: #0C7CE6 !important;
        color:white !important;
        font-weight: 600;
        border:none !important;
        border-radius: .25rem !important;
    }
</style>
<div >
    <nav>
        <div class="nav nav-tabs" id="active-tab" role="tablist">
          <a class="nav-link t active" id="nav-home-tab" data-toggle="tab" href="#active1" role="tab" aria-controls="nav-home" aria-selected="true">Active</a>
          <a class="nav-link t" id="nav-profile-tab" data-toggle="tab" href="#active0" role="tab" aria-controls="nav-profile" aria-selected="false">Deactivated</a>
        </div>
      </nav>
      <div class="tab-content" id="active-tabContent">
        <div class="tab-pane fade show shadow p-3 active" id="active1" role="tabpanel" aria-labelledby="nav-home-tab">
            @include('admin.offer.header')
            @foreach ($offer1 as $offer)
                @include('admin.offer.single',['offer'=>$offer])
            @endforeach
        </div>
        <div class="tab-pane  shadow p-3 fade" id="active0" role="tabpanel" aria-labelledby="nav-profile-tab">
            @include('admin.offer.header')
            
            @foreach ($offer0 as $offer)
                @include('admin.offer.single',['offer'=>$offer])
                
            @endforeach
        </div>
      </div>
    
</div>





@endsection
@section('js')

@endsection
