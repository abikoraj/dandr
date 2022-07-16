<div class="col-md-7">
    <div class="shadow">
        <h5 class="mb-0 p-2">
            Sales
        </h5>
        @php
            $total=0;
            $ptotal=0;
            $dtotal=0;
        @endphp
        <div class="py-2">
            <table class="table">
                <tr>
                    <th>
                        Type
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
                @foreach ($salesData as $key=>$data)
                    <tr>
                        <td>
                            {{$key}} Sales
                        </td>
                        <td>
                            {{$data->total??0}}
                            @php
                                $total+=$data->total??0;
                            @endphp
                        </td>
                        <td>
                            {{$data->paid??0}}
                            @php
                                $ptotal+=$data->paid??0;
                            @endphp
                        </td>
                        <td>
                            {{$data->due??0}}
                            @php
                                $dtotal+=$data->due??0;
                            @endphp
                        </td>


                    </tr>

                @endforeach
                <tr>
                    <th>
                        Total Amount
                    </th>
                    <th>
                        {{$total}}
                    </th>
                    <th>
                        {{$ptotal}}
                    </th>
                    <th>
                        {{$dtotal}}
                    </th>
                </tr>
            </table>


        </div>
    </div>
</div>
