<div class="b-1 mt-4 p-3">

    <style>
        td,th{
            border:1px solid black;
        }
        table{
            width:100%;
            border-collapse: collapse;
        }
        thead {display: table-header-group;}
        tfoot {display: table-header-group;}

    </style>
    @php
        $totalpdue=0;
        $totaldue=0;
        $totaladvance=0;
        $nettotal=0;
        $paidtotal=0;
        $duetotal=0;
        $balancetotal=0;

    @endphp
    <table class="table">
        <tr>
            <th>
                No
            </th>
            <th>
                Name
            </th>
            <th>
                Prev Due
            </th>
            <th>
                Prev Advance
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
                 Advance
            </th>

        </tr>
        @php
            $totalQty=0;
            $total=0;
        @endphp
        @foreach ($elements as $data)
            @php
                $_data=(object)$data;
            @endphp
            <tr>
                <td>
                    {{$_data->id}}
                </td>
                <td>
                    {{$_data->name}}
                </td>
                <td>
                    {{(float)$_data->prevdue}}
                    @php
                        $totalpdue+=(float)$_data->prevdue;
                    @endphp
                </td>
                <td>
                    {{(float)$_data->prevadvance}}
                    @php
                        $totaladvance+=(float)$_data->prevadvance;
                    @endphp
                </td>
                {{-- <td>
                    {{$_data->qty}}
                    @php
                        $totalQty+=$_data->qty;
                    @endphp
                </td> --}}
                <td>
                    {{(float)$_data->total}}
                    @php
                        $total+=(float)$_data->total;
                    @endphp
                </td>
                <td>
                    {{(float)$_data->paid}}
                    @php
                         $paidtotal+=(float)$_data->paid;
                    @endphp
                </td>
                <td>
                    {{(float)$_data->due??0}}
                    @php
                         $duetotal+=(float)$_data->due??0;
                    @endphp
                </td>
                <td>
                    {{(float)$_data->advance??0}}
                    @php

                        $balancetotal+=(float)$_data->advance??0;
                    @endphp
                </td>
            </tr>

        @endforeach
            <tr style="font-weight:600 !important;">
                <th colspan="2">
                    Total
                </th>
                <th>
                    {{$totalpdue}}
                </th>
                <th>
                    {{ $totaladvance}}
                </th>
                <th>
                    {{$total}}
                </th>
                <th>
                    {{$paidtotal}}
                </th>
                <th>
                    {{$duetotal}}
                </th>
                <th>
                    {{  $balancetotal}}
                </th>
            </tr>
        {{-- <tr class="font-weight-bold">
            <td colspan="2" class="text-right">
                Total
            </td>
            <td>
                {{$totalQty}}
            </td>
            <td>
                {{$total}}
            </td>
        </tr> --}}
    </table>
</div>
