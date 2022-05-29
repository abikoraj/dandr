<div class="py-3">
    <span class="btn btn-success" onclick="printDiv('table-1');"> Print Report</span>

</div>
<div id="table-1">
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
    <h3>
        Stock Report {{$center!=''?" - ".$center:' - All Centers / Branch'}}
    </h3>
    <table>
        <thead>
            @php
                $i = 1;
            @endphp
            <tr>

                <th>
                    SN
                </th>
                <th>
                    Item
                </th>
                <th>
                    Qty
                </th>
                <th>
                    Current Stock (Rs. )
                </th>

            </tr>
        </thead>
        <tbody>
            @php
                $ftot = 0;
            @endphp
            @foreach ($datas as $data)
                <tr>
                    <td>
                        {{ $i++ }}
                    </td>

                    <td>
                        {{ $data->title }}
                    </td>

                    <td>
                        {{ (float)$data->qty }}
                    </td>
                    <td>
                        {{ (float)$data->current_stock }}
                        @php $ftot+=$data->current_stock @endphp
                    </td>

                </tr>
            @endforeach
            <tr>
                <th colspan="3">Total</th>
                <th>{{ $ftot }}</th>
            </tr>
        </tbody>
    </table>
</div>
