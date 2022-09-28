@extends('admin.layouts.app')
@section('title', 'Employee Chalan - closing')
@section('head-title')
    <a href="{{ route('admin.chalan.index') }}">Employee Chalans</a>
    /{{ $chalan->user->name }}
    / {{ _nepalidate($chalan->date) }} #{{ $chalan->id }} / Detail
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
@endsection
@section('content')
    <div class="shadow p-2 mb-3">

        <h5>
            Customer Detail
        </h5>
        <table class="table">
            <tr>
                <th>
                    Customer
                </th>
                <th>
                    Items
                </th>
                <th>
                    Total
                </th>
                <th>
                    Paid
                </th>
                <th>
                    Due
                </th>
                <th>
                    Balance
                </th>
            </tr>
            @foreach ($users as $user)
                <tr>
                    <th>
                        {{ $user->name }}
                    </th>
                    <td>
                        @foreach ($user->sales as $item)
                            {{ $item->title }} X {{ $item->rate }} {{ $item->unit }},
                        @endforeach
                    </td>
                    <td>
                        {{ $user->sales_amount }}

                    </td>
                    <td>
                        {{ $user->payments_amount }}
                    </td>
                    <td>
                        {{ $user->due }}
                    </td>
                    <td>
                        {{ $user->balance }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <th colspan="2">Total</th>
                <td>{{ $users->sum('sales_amount') }}</td>
                <td>{{ $users->sum('payments_amount') }}</td>
                <td>{{ $users->sum('due') }}</td>
                <td>{{ $users->sum('balance') }}</td>
            </tr>
        </table>
    </div>
    <div class="shadow p-2 mb-3">
        <h5>
            Item Detail
        </h5>
        <table class="table">
            <tr>

                <th>
                    Items
                </th>
                <th>
                    Issued
                </th>
                <th>
                    Sold
                </th>
                <th>
                    Waste
                </th>
                <th>
                    Returned
                </th>
            </tr>
            @foreach ($chalanItems as $item)
                <tr>
                    <th>
                        {{ $item->title }}
                    </th>
                    <td>
                        {{ $item->qty }} {{ $item->unit }}
                    </td>
                    <td>
                        {{ $item->sold }} {{ $item->unit }}

                    </td>
                    <td>
                        {{ $item->wastage }} {{ $item->unit }}

                    </td>
                    <td>
                        {{ $item->newremaning }} {{ $item->unit }}
                    </td>

                </tr>
            @endforeach
        </table>
    </div>
    @php
        $collection=json_decode($chalan->notes);
    @endphp

    @if ($collection!=null)
        <div class="shadow p-2 mb-3">
            <h5>
                Collection Detail
            </h5>
            <table class="table">
                <tr>

                    <th>
                        Method
                    </th>
                    <th>
                        Particular
                    </th>
                    <th>
                        Amount
                    </th>
                    
                </tr>
                
            
                <tr>
                    <th>
                        Cash
                    </th>
                    <th>
                        @php
                            $cashCollection=0;
                        @endphp
                        @foreach ($collection->notes as $note)
                            {{$note->note}} X {{$note->amount/$note->note}},
                            @php
                                $cashCollection+=$note->amount;
                            @endphp
                        @endforeach
                    </th>
                    <th>
                        {{$cashCollection}}
                    </th>
                </tr>
                @foreach ($collection->bank as $bank)
                    <tr>
                        <th>
                            Bank
                        </th>
                        <th>
                            {{$bank->name}}
                        </th>
                        <th>
                            {{$bank->amount}}
                        </th>
                    </tr>
                @endforeach
            </table>
        </div>        
    @endif
    
  


@endsection
