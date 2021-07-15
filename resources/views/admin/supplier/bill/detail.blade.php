@extends('admin.layouts.app')
@section('title')
    {{$bill->user->name}} / Bills / {{$bill->billno}}
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('head-title')
  <a href="{{route('admin.supplier.bill')}}">Supplier Bill</a> / {{$bill->user->name}} / Bills / {{$bill->billno}}

@endsection

@section('content')
  <style>
    label{
      font-weight: 600;
      margin-bottom: 3px;
    }
  </style>
    <div class="row">
      <div class="col-md-6">
        <label for="">supplier </label>
        <div>{{$bill->user->name}}</div>
      </div>
      <div class="col-md-3">
        <label for="">Bill No </label>
        <div>{{$bill->billno}}</div>
      </div>
      <div class="col-md-3">
        <label for="">Date </label>
        <div>{{_nepalidate($bill->date)}}</div>
      </div>
    
    </div>
    <hr>
    <div class="row">
      <div class="col-md-7 b-1 py-3">
        <h5>
          Bill Items
        </h5>
        <table class="table">
          <tr>
            <th>
              Particular
            </th>
            <th>
              Rate
            </th>
            <th>
              Amount
            </th>
            <th>
              Total
            </th>
          </tr>
          @php
              $tot=0;
          @endphp
          @foreach ($bill->billitems as $item)
              <tr>
                <td>
                  {{$item->title}}
                </td>
                <td>
                  {{$item->qty}}
                </td>
                <td>
                  {{$item->rate}}
                </td>
                <td>
                  {{$item->qty*$item->rate}}
                  @php
                      $tot+=$item->qty*$item->rate;
                  @endphp
                </td>
              </tr>
          @endforeach
          <tr>
            <td class="text-right font-weight-bold" colspan="3">Total</td>
            <td>{{$tot}}</td>
          </tr>
          <tr>
            <td class="text-right font-weight-bold" colspan="3">Discount</td>
            <td>{{$bill->discount}}</td>
          </tr>
          <tr>
            <td class="text-right font-weight-bold" colspan="3">Taxable</td>
            <td>{{$bill->taxable}}</td>
          </tr>
          <tr>
            <td class="text-right font-weight-bold" colspan="3">Tax</td>
            <td>{{$bill->tax}}</td>
          </tr>
          <tr>
            <td class="text-right font-weight-bold" colspan="3">Grand Total</td>
            <td>{{$bill->total}}</td>
          </tr>
          <tr>
            <td class="text-right font-weight-bold" colspan="3">Paid</td>
            <td>{{$bill->paid}}</td>
          </tr>
          <tr>
            <td class="text-right font-weight-bold" colspan="3">Due</td>
            <td>{{$bill->due}}</td>
          </tr>
        </table>
      </div>
      <div class="col-md-5 b-1 py-3">
        <h5>
          Bill Expenses
        </h5>
        <table class="table">
          <tr>
            <th>Title</th>
            <th>Amount</th>
          </tr>
          @foreach ($bill->expense as $exp)
              <tr>
                <td>
                  {{$exp->title}}
                </td>
                <td>
                  {{$exp->amount}}
                </td>
              </tr>
          @endforeach
        </table>
      </div>
    </div>
@endsection

