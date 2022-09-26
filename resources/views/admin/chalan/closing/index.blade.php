@extends('admin.layouts.app')
@section('title', 'Employee Chalan - closing')
@section('head-title')
    <a href="{{ route('admin.chalan.index') }}">Employee Chalans</a>
    /{{ $chalan->user->name }}
    / {{ _nepalidate($chalan->date) }} #{{ $chalan->id }} / closing
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
                        {{ $item->remaning }} {{ $item->unit }}

                    </td>

                </tr>
            @endforeach
        </table>
    </div>
    @php
    @endphp
    <form action="">
        @csrf
        <div class="row">
            <div class="col-6">
                <div class="shadow p-2 mb-3">
                    <h5>
                        Cash Collection
                    </h5>
                    <table class="table">
                        <tr>
                            <th>
                                Notes
                            </th>
                            <th>
                                Qty
                            </th>
                            <th>Amount</th>
                        </tr>

                        @foreach ($notes as $note)
                            <tr>
                                <th>
                                    {{ $note }}
                                </th>
                                <th>
                                    <input type="number" id="note_{{ $note }}" min="0" value=""
                                        data-value="{{ $note }}" class="form-control notes"
                                        onchange="calculateNote()" oninput="calculateNote()">
                                    <input type="hidden" name="note_{{ $note }}"
                                        id="input_note_{{ $note }}">
                                </th>
                                <td id="note_amount_{{ $note }}">
                                    0
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="2">Total</th>
                            <td id="noteTotal"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-6">
                <div class="shadow p-2 mb-3">
                    <h5>
                        Bank Collection
                    </h5>
                    <table class="table">
                        <tr>
                            <th>
                                Bank
                            </th>
                            <th>Amount</th>
                        </tr>

                        @foreach ($banks as $bank)
                            <tr>
                                <th>
                                    {{ $bank->name }}
                                </th>
                                <th>
                                    <input type="number" class="form-control banks" name="bank_{{ $bank->account_id }}"
                                        id="bank_{{ $bank->account_id }}" min="0" value="0"
                                        onchange="calculateNote()" oninput="calculateNote()">
                                </th>
                            </tr>
                        @endforeach
                        <tr>
                            <th>Total</th>
                            <td id="bankTotal">

                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-12">
                <div class="shadow p-2 mb-3">
                    <div class="row">
                        <div class="col-4">
                            <strong>Payment Amount</strong>
                            <div>{{$users->sum('payments_amount')}}</div>
                        </div>
                        <div class="col-4">
                            <strong>Collection Amount</strong>
                            <div id="collectionTotal">0</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

@endsection
@section('js')
    <script>
        function calculateNote() {
            let noteAmount = 0;
            $('.notes').each(function(index, element) {
                const qty = parseInt(element.value);
                const value = parseInt(element.dataset.value);
                let amount = 0;
                console.log('admin', qty, value);
                if (!(isNaN(qty))) {
                    amount = (qty * value);
                }

                noteAmount += amount;
                $('#input_note_' + value).val(amount);
                $('#note_amount_' + value).html(amount);
            });
            $('#noteTotal').html(noteAmount);

            let bankAmount = 0;
            $('.banks').each(function(index, element) {
                let amount = parseInt(element.value);
                if (!(isNaN(amount))) {
                    bankAmount += amount
                }
            });
            $('#bankTotal').html(bankAmount);
            $('#collectionTotal').html((bankAmount+noteAmount));
        }
    </script>

@endsection
