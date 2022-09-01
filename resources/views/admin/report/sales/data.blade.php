@php
$arr = ['Farmer', 'Distributer', 'Employee','Counter'];
// $cats=explode()
$tracktop = 0;
$trackbottom = 0;
@endphp
<hr>
<h5>
    Sales Report Full Item Wise
</h5>
<table class="table">
    <tr>
        <th>
            Item
        </th>
        <th>
            Qty
        </th>
        <th>
            Amount
        </th>
    </tr>
    @php
        $tot=0;
        $totQTY=0;
    @endphp
    @foreach ($itemAmount as $item)
        <tr>
            <td>
                {{$item->name}}
            </td>
            <td>
                {{$item->qty}}
            </td>
            <td>
                {{$item->total}}
            </td>
            @php
                $tot+=$item->total;
                $totQTY+=$item->qty;
            @endphp
        </tr>
    @endforeach

    <tr>
        <th>Total</th>
        <th>{{$totQTY}}</th>
        <th>{{$tot}}</th>
    </tr>
</table>

<hr>

<h5>
    Sales Report Party Wise
</h5>
<ul class="nav nav-tabs" id="myTab" role="tablist">

    @for ($i = 0; $i < 4; $i++)
        @php
            $dataType = $arr[$i];
        @endphp
        @if (in_array($dataType,$cats))
            
            <li class="nav-item">
                <a class="nav-link {{ $tracktop == 0 ? 'active' : '' }}" id="home-tab" data-toggle="tab"
                    href="#{{ $dataType }}-1" role="tab" aria-controls="home" aria-selected="true">{{ $dataType }}
                    Sales</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#{{ $dataType }}-2" role="tab"
                    aria-controls="profile" aria-selected="false">{{ $dataType }} Sales Product Wise</a>
            </li>
            @php
                $tracktop++;
            @endphp
        @endif
    @endfor

</ul>
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
<div class="tab-content" id="myTabContent">
    @for ($i = 0; $i < 4; $i++)
        @php
            $dataType = $arr[$i];
        @endphp
        @if (in_array($dataType,$cats))
            <div class="tab-pane fade  {{ $trackbottom == 0 ? 'active show' : '' }}" id="{{ $dataType }}-1"
                aria-labelledby="home-tab">
                <div class="py-3">
                    <span class="btn btn-success" onclick="printDiv('{{ $dataType }}-table-1');"> Print Report</span>

                </div>
                <div id="{{ $dataType }}-table-1">

                    <table>
                        <thead>
                            @php
                                $id = 1;
                            @endphp
                            <tr>
                                <th>
                                    SN
                                </th>
                                <th>
                                    {{ $dataType }} Name
                                </th>
                                <th>
                                    Amount
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $ftot = 0;
                            @endphp
                            @foreach ($byName[$i] as $data)
                                <tr>
                                    <td>
                                        {{ $id++ }}
                                    </td>

                                    <td>
                                        {{ $data->name }} ( {{ $data->no ?? '' }} )
                                    </td>

                                    <td>
                                        {{ $data->total }}
                                        @php
                                            $ftot += $data->total;
                                        @endphp
                                    </td>
                                    {{-- <td>
                                    {{$sellitem->due}}
                                </td> --}}
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="2">Total</th>
                                <th>{{ $ftot }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="tab-pane fade " id="{{ $dataType }}-2"
                aria-labelledby="home-tab">
                <div class="py-3">
                    <span class="btn btn-success" onclick="printDiv('{{ $dataType }}-table-2');"> Print Report</span>
                </div>
                <div id="{{ $dataType }}-table-2">

                    <table>
                        <thead>
                            @php
                                $id = 1;
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
                                    Amount
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $ftot = 0;
                            @endphp
                            @foreach ($byItem[$i] as $data)
                                <tr>
                                    <td>
                                        {{ $id++ }}
                                    </td>

                                    <td>
                                        {{ $data->name }}
                                    </td>
                                    <td>
                                        {{ $data->qty }}
                                    </td>
                                    <td>
                                        {{ $data->total }}
                                        @php
                                            $ftot += $data->total;
                                        @endphp
                                    </td>
                                    {{-- <td>
                                    {{$sellitem->due}}
                                </td> --}}
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="3">Total</th>
                                <th>{{ $ftot }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
            @php
                $trackbottom++;
            @endphp
        @endif

    @endfor

</div>
