<div class="col-md-6">
    <div class="shadow">
        <h3 class="mb-0 p-2">
            Farmer Sell of {{$month->year}} , {{$month->month_name}}
        </h3>
        <hr class="m-0">
        <table class="table">
            <thead>
                <tr>
                    <th>
                        Date
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
                </tr>

                @for ($date = $range[1]; $date <=$range[2]; $date++)
                    @php
                        $data=$milkData->where('date',$date)->first();
                    @endphp
                    <tr>
                        <td>
                            {{_nepalidate($date)}}
                        </td>
                        <td>
                            {{truncate_decimals($data->m_amount??0)}}
                        </td>
                        <td>
                            {{truncate_decimals($data->e_amount??0)}}
                        </td>
                        <td>
                            {{truncate_decimals($data->m_amount??0)+truncate_decimals($data->e_amount??0)}}
                        </td>
                    </tr>
                @endfor
                <tr>
                    <th>
                        Total

                    </th>
                    <th>
                        {{$milkData->sum('m_amount')}}
                    </th>
                    <th>
                        {{$milkData->sum('e_amount')}}
                    </th>
                    <th>
                        {{$milkData->sum('m_amount')+$milkData->sum('e_amount')}}
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>
