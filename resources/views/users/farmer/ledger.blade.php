@if (count($farmer->ledger) > 0)
    <div class="col-md-12 mt-3">
        <div style="border: 1px solid rgb(136, 126, 126); padding:1rem;">

            <div id="ledger-data">
                <style>
                    @media print {
                        td {
                            font-size: 1.2rem !important;
                            font-weight: 600 !important;
                        }




                    }

                    td,
                    th {
                        border: 1px solid black !important;
                        padding: 2px !important;
                        font-weight: 600 !important;
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
                <strong>Ledger</strong>
                <hr>
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Particular</th>
                            <th>Cr. (Rs.)</th>
                            <th>Dr. (Rs.)</th>
                            <th>Balance (Rs.)</th>
                            <th class="d-print-none"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($prev != 0)
                            <tr>
                                <td>
                                    --
                                </td>
                                <td>Previous Balance</td>
                                <td>{{ $prev < 0 ? -1 * $prev : '' }}</td>
                                <td>{{ $prev > 0 ? $prev : '' }}</td>
                                <td>
                                    {{ $prev > 0 ? 'Dr.' . $prev : ($prev < 0 ? 'Cr.' . -1 * $prev : '--') }}
                                </td>
                                <td class="d-print-none"></td>
                            </tr>
                        @endif

                        @foreach ($farmer->ledger as $l)
                            <tr>
                                <td>{{ _nepalidate($l->date) }}</td>
                                <td>{{ $l->title }}</td>

                                <td>
                                    @if ($l->type == 1)
                                        {{ (float) $l->amount }}
                                    @endif
                                </td>
                                <td>
                                    @if ($l->type == 2)
                                        {{ (float) $l->amount }}
                                    @endif
                                </td>
                                <td>
                                    {{ $l->amt > 0 ? 'Dr.' . $l->amt : ($l->amt < 0 ? 'Cr.' . -1 * $l->amt : '--') }}
                                </td>
                              
                            </tr>
                        @endforeach
                        @if ( env('farmer_detail_milk_ledger',0)==1 && !$milkloaded)
                        <td>
                            --
                        </td>
                        <td>Milk amount ({{$farmer->milkamount}} L)</td>
                        <td>{{ $farmer->grandtotal }}</td>
                        @php
                            $closing-=$farmer->grandtotal
                        @endphp
                        <td></td>
                        <td>
                            {{ $closing > 0 ? 'Dr.' . $closing : ($closing < 0 ? 'Cr.' . -1 * $closing: '--') }}
                            

                        </td>
                        <td class="d-print-none"></td>
                        @endif
                        @if ($closing != 0)
                            <tr>
                                <td>
                                    --
                                </td>
                                <td>Closing Balance</td>
                                <td>{{ $closing > 0 ? $closing : '' }}</td>
                                <td>{{ $closing < 0 ? -1 * $closing : '' }}</td>
                                <td>
                                    --

                                </td>
                                <td class="d-print-none"></td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
