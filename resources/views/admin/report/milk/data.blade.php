@foreach ($data1 as $key=>$milkdatas)
    
    <div id="center-{{$key}}" class="p-4 shadow m-3">
        <div>
            <button class="btn success" style="float: right;" onclick="printDiv('center-data-{{$key}}')">Print</button>
        </div>

        <div id="center-data-{{$key}}">
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
            <h2 style="text-align: center;margin-bottom:0px;font-weight:800;font-size:2rem;">
                Collection Center : {{\App\Models\Center::find($key)->name}}
            </h2>
            <table>
                <thead>
                    <tr>
                        <th>
                            Farmer No
                        </th>
                        <th>
                            Farmer Name
                        </th>
                        <th>
                            Morning Amount
                        </th>
                        <th>
                            Evening Amount
                        </th>
                        <th>
                            Total Amount
                        </th>
                    </tr>

                </thead>
                <tbody>
                    @foreach ($milkdatas as $milkdata)
                        <tr>
                            <td>
                                {{$milkdata->no}}
                            </td>
                            <td>
                                {{$milkdata->name}}
                            </td>
                            <td>
                                {{$milkdata->m_amount}}
                            </td>
                            <td>
                                {{$milkdata->e_amount}}
                            </td>
                            <td>
                                {{$milkdata->e_amount+$milkdata->m_amount}}
                            </td>

                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">
                            Total
                        </th>
                        <th>
                            {{$milkdatas->sum('m_amount')}}
                        </th>
                        <th>
                            {{$milkdatas->sum('e_amount')}}
                        </th>
                        <th>
                            {{$milkdatas->sum('m_amount')+$milkdatas->sum('e_amount')}}
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endforeach
