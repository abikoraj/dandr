<style>
    .d-print-show {
        display: none !important;
    }
</style>
<div class="row">

    <div class="col-md-12 mt-3">
        <div style="border: 1px solid rgb(136, 126, 126); padding:.5rem;">
            <strong>Ledger</strong> <span class="btn btn-success" onclick="printDiv('ledger');">Print</span>
            <hr>

            <div id="ledger" style="overflow: scroll;">
                <div class="d-print-show">
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

                        .d-show-rate {

                            @if (env('showdisrate', 0) == 1)display: inline;
                        @else display: none !important;
                            @endif
                        }
                    </style>
                    <h2 style="text-align: center;margin-bottom:0px;font-weight:800;font-size:2rem;">
                        {{ env('APP_NAME', 'Dairy') }} <br>

                    </h2>

                    <div style="font-weight:800;text-align:center;">
                        <span class="mx-3"> Ledger For : {{ $user->name }} , </span>
                        {!! $title !!}
                    </div>
                </div>
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <tr>
                        <th>Date</th>
                        <th>Particular</th>
                        <th>Cr. (Rs.)</th>
                        <th>Dr. (Rs.)</th>
                        <th>Balance (Rs.)</th>
                    </tr>
                    @php
                        $cr = 0;
                        $dr = 0;
                    @endphp
                    @if ($prev != 0)

                        <tr>
                            <td>
                                --
                            </td>
                            <td>
                                Previous Balance
                            </td>
                            @if ($prev > 0)
                                <td>

                                </td>
                                <td>
                                    {{ $prev }}
                                </td>
                                <td>
                                    Dr.{{ $prev }}
                                    @php
                                        $dr += $prev;
                                    @endphp
                                </td>
                            @elseif ($prev < 0)
                                <td>
                                    {{ -1 * $prev }}
                                </td>
                                <td>

                                </td>
                                <td>
                                    Cr.{{ -1 * $prev }}
                                    @php
                                        $cr += -1 * $prev;
                                    @endphp
                                </td>
                            @else
                                <td>
                                    --
                                </td>
                                <td>
                                    --
                                </td>
                                <td>
                                    --
                                </td>
                            @endif
                        </tr>
                    @endif
                    @foreach ($arr as $l)
                        <tr>
                            <td>{{ _nepalidate($l->date) }}</td>
                            <td>{!! $l->title !!}</td>

                            <td>
                                @if ($l->type == 1)
                                    {{ (float) $l->amount }}
                                    @php
                                        $cr += $l->amount;
                                    @endphp
                                @endif
                            </td>
                            <td>
                                @if ($l->type == 2)
                                    {{ (float) $l->amount }}
                                    @php
                                        $dr += $l->amount;
                                    @endphp
                                @endif
                            </td>
                            <td>
                                @if ($l->amt > 0)
                                    Dr. {{ (float) $l->amt }}
                                @elseif ($l->amt < 0)
                                    Cr. {{ (float) (-1 * $l->amt) }}
                                @else
                                    --
                                @endif
                            </td>
                          
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">
                            @if ($cr > $dr)
                                Advance
                            @elseif($dr > $cr)
                                Due Remaning
                            @endif
                        </th>
                        <th>
                            @if ($dr > $cr)
                                {{ $dr - $cr }}
                            @endif
                        </th>
                        <th>
                            @if ($cr > $dr)
                                {{ $cr - $dr }}
                            @endif

                        </th>
                        <td>

                        </td>
                     
                    </tr>
                    <tr>
                        <th colspan="2">

                        </th>
                        <th>
                            {{ $dr > $cr ? $dr : $cr }}
                        </th>
                        <th>
                            {{ $dr > $cr ? $dr : $cr }}

                        </th>
                        <th>

                        </th>
                       
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
