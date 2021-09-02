@extends('pos.layout.index')
@section('title')
<span class="text-white">
  {{env('APP_NAME','laravel')}} - {{$counter->name}}
</span>
@endsection
@section('content')
<div class="row m-0 h-100 ">
    <div class="col-md-8">
      <div id="panel1">
        <div id="barcode-container">
          <input type="text" placeholder="Enter Item Code OR Scan BarCode" id="barcode">
          <img src="images/barcode.svg" alt="" srcset="">
          <img src="images/search.svg" alt="" srcset="">
        </div>
        
        @include('pos.layout.particular')
        @include('pos.layout.particular_calculation')
      
      </div>
    </div>
    <div class="col-md-4 ps-0">
   
      @include('pos.layout.item_selector')
      @include('pos.layout.customer_selector')
      @include('pos.layout.payment')
    </div>
</div>
@endsection