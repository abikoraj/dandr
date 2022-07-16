<div class="col-md-5">
    <div class="shadow">
        <h5 class="mb-0 p-2">
            Milk Collection
        </h5>
        @php
            $milkTotal=0;
        @endphp
        <div class="py-2">
            <table class="table">
                <tr>
                    <th>
                        Center
                    </th>
                    <th>
                        Collection
                    </th>
                </tr>
                @foreach ($milkData as $data)
                    <tr>
                        <td>
                            {{$data->center}}
                        </td>
                        <td>
                            {{$data->amount}}
                            @php
                                $milkTotal+=$data->amount;
                            @endphp
                        </td>

                    </tr>

                @endforeach
                <tr>
                    <th>
                        Total Amount
                    </th>
                    <th>
                        {{$milkTotal}}
                    </th>
                </tr>
            </table>


        </div>
    </div>
</div>
{{-- <div class="col-md-12">
    <div class="shadow">
        <h3 class="mb-0 p-2">
            Milk Collection of {{$month->year}} , {{$month->month_name}}
        </h3>
        <hr class="m-0">
        <table class="table">
            <thead>
                <tr>
                    <th>
                        Date
                    </th>
                    <th>
                        Morning
                    </th>
                    <th>
                        Evening
                    </th>
                    <th>
                        Total
                    </th>
                </tr>

                @for ($date = $range[1]; $date <= $range[2]; $date++)
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
</div> --}}
{{-- <div class="col-12 mb-3 shadow">
    <canvas id="milk"></canvas>
</div>
<script>
    datas['milk'] = {
        id: milk,
        title: "Milk Collection of {{ $month->year }} , {{ $month->month_name }}",
        data: {
            labels: [],
            datasets: [{
                    label:"Morning Collection",
                    backgroundColor: 'rgba(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: []
                },
                {
                    label:"Evening Collection",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    data: []
                },
                {
                    label:"Total Collection",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    data: []
                },

            ]
        }
    };

    window.addEventListener('load', (event) => {
        const milkData = {!! json_encode($milkData, JSON_NUMERIC_CHECK) !!}
        for (let date = range[1]; date <= range[2]; date++) {
            const data = milkData.find(o => o.date == date);
            datas['milk'].data.labels.push(toNepaliDate(date));
            if (data != undefined) {
                datas['milk'].data.datasets[0].data.push(data.m_amount);
                datas['milk'].data.datasets[1].data.push(data.e_amount);
                datas['milk'].data.datasets[2].data.push(data.m_amount + data.e_amount);
            } else {
                datas['milk'].data.datasets[0].data.push(0);
                datas['milk'].data.datasets[1].data.push(0);
                datas['milk'].data.datasets[2].data.push(0);
            }
        }

        $milkChart = new Chart(
            document.getElementById('milk'), {
                type: 'line',
                data: datas['milk'].data,
                options: {}
            }
        );


    });
</script> --}}
