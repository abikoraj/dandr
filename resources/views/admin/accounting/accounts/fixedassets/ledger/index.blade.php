@extends('admin.layouts.app')
@section('css')
    <style>
        .sided-ledger-holder {
            /* border: 1px solid black; */
        }

        .sided-ledger {
            margin: 0px;
            border-collapse: collapse;
        }

        .sided-ledger th,
        .sided-ledger td {
            min-height: 22px;
            border: 1px solid black !important;


        }

        .sided-ledger th:nth-child(1) {
            width: 10%;
            border-right: 1px solid black;
        }

        .sided-ledger th:nth-child(2) {
            width: 30%;
            border-right: 1px solid black;

        }

        .sided-ledger th:nth-child(3) {
            width: 10%;
            border-right: 1px solid black;
        }

        .sided-ledger th:nth-child(4) {
            width: 10%;
            border-right: 1px solid black;
        }

        .sided-ledger th:nth-child(5) {
            width: 30%;
            border-right: 1px solid black;
        }

        .sided-ledger th:nth-child(6) {
            width: 10%;
        }
    </style>
@endsection
@section('title', 'Accounts')
@section('head-title')
    <a href="{{ route('admin.accounting.index') }}">Accounting</a>
    / <a href="{{ route('admin.accounting.accounts.index') }}">Accounts</a>
    / {{ $account->name }} / {{ $fy->name }} / Ledger
@endsection
@section('content')

    {{-- <div class="row m-0">
        <div class="col-6 p-0">
            <h5 class="p-1">
                Dr.
            </h5>

        </div>
        <div class="col-6 p-0">
            <h5 class="text-right p-1">
                Cr.
            </h5>
        </div>
        <div class="col-6 sided-ledger-holder p-0">

            <table class="sided-ledger table">
                <thead>
                    <tr>
                        <th>
                            Date
                        </th>
                        <th>
                            Particular
                        </th>
                        <th>
                            Amount
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ledgers->where('type', 2) as $ledger)
                        <tr>
                            <th>
                                {{ _nepalidate($ledger->date) }}
                            </th>
                            <th>
                                {{ $ledger->title }}
                            </th>
                            <th>
                                {{ $ledger->amount }}
                            </th>
                        </tr>
                    @endforeach
                    @for ($i = 0; $i < $drcount; $i++)
                        <tr><th></th><th></th><th></th></tr>
                    @endfor
                </tbody>
            </table>
        </div>
        <div class="col-6 sided-ledger-holder p-0">
          
            <table class="sided-ledger table">
                <thead>
                    <tr>
                        <th>
                            Date
                        </th>
                        <th>
                            Particular
                        </th>
                        <th>
                            Amount
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ledgers->where('type', 1) as $ledger)
                        <tr>
                            <th>
                                {{ _nepalidate($ledger->date) }}
                            </th>
                            <th>
                                {{ $ledger->title }}
                            </th>
                            <th>
                                {{ $ledger->amount }}
                            </th>
                        </tr>
                    @endforeach
                    @for ($i = 0; $i < $crcount; $i++)
                        <tr><th></th><th></th><th></th></tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
    <div id="data"></div> --}}
    @php
        $crlist = $ledgers->where('type', 1);
        $drlist = $ledgers->where('type', 2);
    @endphp
    <table class="w-100 sided-ledger">
        <tr>
            <th>
                Date
            </th>
            <th>
                Particular
            </th>
            <th>
                Amount
            </th>
            <th>
                Date
            </th>
            <th>
                Particular
            </th>
            <th>
                Amount
            </th>
        </tr>
        @for ($i = 0; $i < $max; $i++)
            <tr>

                @php
                    $cr = $crlist->values()->get($i);
                    $dr = $drlist->values()->get($i);
                @endphp
                @if (isset($dr))
                    <td>
                        {{ _nepalidate($dr->date) }}
                    </td>
                    <td>
                        {{ $dr->title }}
                    </td>
                    <td>
                        {{ (float)$dr->amount }}
                    </td>
                @else
                    <td></td>
                    <td></td>
                    <td></td>
                @endif

                @if (isset($cr))
                    <td>
                        {{ _nepalidate($cr->date) }}
                    </td>
                    <td>
                        {{ $cr->title }}
                    </td>
                    <td>
                        {{ (float)$cr->amount }}
                    </td>
                @else
                    <td></td>
                    <td></td>
                    <td></td>
                @endif
            </tr>
        @endfor
            <tr>
                <th></th>
                <th></th>
                <th>{{$drlist->sum('amount')}}</th>
                <th></th>
                <th></th>
                <th>{{$crlist->sum('amount')}}</th>
            </tr>
    </table>
@endsection
@section('js')


@endsection
