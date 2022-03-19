<hr>
<div class="row ">
    <div class="col-md-6 pr-0">
        <div style="border: 1px solid rgb(136, 126, 126); padding:1rem;height:100%;">
            {{-- <button class="btn btn-success" onclick="printDiv('milk-data');">Print</button> --}}
            <div id="milk-data">
                <style>
                    td,
                    th {
                        border: 1px solid black;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }

                    thead {
                        display: table-header-group;
                    }

                    tfoot {
                        display: table-header-group;
                    }

                </style>
                <strong>Milk Data</strong>
                <hr>
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <tr>
                        <th>Date</th>
                        <th>Amount (L)</th>
                        <th>Session (L)</th>
                    </tr>
                    @php
                        $m = 0;
                        $snf = 0;
                        $fat = 0;
                        $count = 0;
                    @endphp
                    @foreach ($milkData['milk'] as $milk)
                        <tr>
                            <td>{{ _nepalidate($milk->date) }}</td>
                            <td>{{ $milk->amount }}</td>
                            <td> {{ sessionType($milk->session) }}</td>

                        </tr>
                        @php
                            $m += $milk->amount;
                            
                        @endphp
                    @endforeach
                    <tr>
                        <td><strong>Total</strong></td>
                        <td>{{ $m }} Litre</td>
                        <td></td>
                    </tr>
                </table>


            </div>
        </div>

    </div>
    <div class="col-md-6 pl-0">
        <div style="border: 1px solid rgb(136, 126, 126); padding:1rem; height:100%;">

            <div id="snffat-data">
                <style>
                    td,
                    th {
                        border: 1px solid black;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }

                    thead {
                        display: table-header-group;
                    }

                    tfoot {
                        display: table-header-group;
                    }

                </style>
                <strong>Snf & Fats </strong>
                <hr>
                @if ($distributer->is_fixed == 0)
                    <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <tr>
                            <th>Date</th>
                            <th>Snf (%)</th>
                            <th>Fats (%)</th>
                            <td class="d-print-none">

                            </td>
                        </tr>
                        @foreach ($milkData['snffat'] as $sf)
                            <tr>
                                <td>{{ _nepalidate($sf->date) }}</td>
                                <td>{{ $sf->snf }}</td>
                                <td>{{ $sf->fat }}</td>
                                @php
                                    $snf += $sf->snf;
                                    $fat += $sf->fat;
                                    $count += 1;
                                @endphp
                                <td class="d-print-none">
                                    <button class="btn btn-primary btn-sm" data-snffat="{{ $sf->toJson() }}"
                                        onclick="showSnfFatUpdate(this)">
                                        Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" data-snffat="{{ $sf->toJson() }}"
                                        onclick="delSnfFat(this);">
                                        delete
                                    </button>
                                </td>
                            </tr>

                        @endforeach
                    </table>
                @else
                    <h4>Fixed Pricing Has Been Used</h4>
                @endif
                @php
                    $rate = 0;
                    $milkTotal = 0;
                    $snf_avg = 0;
                    $fat_avg = 0;
                    if ($distributer->is_fixed == 1) {
                        $rate = truncate_decimals($distributer->fixed_rate, 2);
                    } else {
                        if ($count > 0) {
                            $snf_avg = truncate_decimals($snf / $count, 2);
                            $fat_avg = truncate_decimals($fat / $count, 2);
                            $rate = truncate_decimals($snf_avg * $distributer->snf_rate + $fat_avg * $distributer->fat_rate + $distributer->added_rate, 2);
                        }
                    }
                    
                    $milkTotal = truncate_decimals($m * $rate, 2);
                    
                @endphp

            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div style="border: 1px solid rgb(136, 126, 126); padding:1rem;height:100%;">
            <table class="w-100">
                <tr>
                    <th>
                        Total Milk
                    </th>
                    @if ($distributer->is_fixed == 0)

                        <th>
                            SNF
                        </th>
                        <th>
                            Fat
                        </th>
                    @endif
                    <th>
                        Per Litre
                    </th>
                    <th>
                        Total Amount
                    </th>
                    <th>
                        Close Date
                    </th>
                </tr>
                <tr>
                    <td>

                        {{ $m }}
                    </td>
                    @if ($distributer->is_fixed == 0)

                        <td>
                            {{ $snf_avg }}
                        </td>
                        <td>
                            {{ $fat_avg }}
                        </td>
                    @endif
                    <td>
                        {{ $rate }}
                    </td>
                    <td>
                        {{ $milkTotal }}
                    </td>
                    <td>
                        <form action="" onsubmit="return milkToLedger(event,this)">
                            @csrf
                            <input type="hidden" name="snf" value="{{ $snf_avg }}">
                            <input type="hidden" name="fat" value="{{ $fat_avg }}">
                            <input type="hidden" name="rate" value="{{ $rate }}">
                            <input type="hidden" name="total" value="{{ $milkTotal }}">
                            <input type="hidden" name="milk" value="{{ $m }}">
                            <input type="hidden" name="distributer_id" value="{{ $distributer->id }}">
                            <input type="hidden" name="is_fixed" value="{{ $distributer->is_fixed }}">
                            <input type="hidden" name="year" value="{{ $milkData['req']->year }}">
                            <input type="hidden" name="month" value="{{ $milkData['req']->month }}">
                            <input type="hidden" name="session" value="{{ $milkData['req']->session }}">
                            @if ($milkData['report'] != null)
                                <input type="hidden" name="id" value="{{ $milkData['report']->id }}">
                                <button>Update</button>
                            @else
                                <input type="text" name="date" id="milk-to-ledger-date" value="{{ $milkData['closingDate'] }}">
                                <input type="hidden" name="id" value="-1">
                                <br>
                                <button>Send To Ledger</button>
                            @endif
                        </form>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
