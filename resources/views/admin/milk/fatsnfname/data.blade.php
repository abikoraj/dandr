<table id="newstable1" class="table table-bordered">
    <thead>
        <tr>
            <th>#No</th>
            <th>Name</th>
            <th> Milk (In Liter)</th>
            <th> Fat</th>
            <th> SNF</th>
            <th></th>
        </tr>
    </thead>
    <tbody id="milk-datas">
        @for ($i = 0; $i < count($farmers); $i++)
            @php
                $farmer = $farmers[$i];
                $milkData = $milkDatas->where('user_id', $farmer->id)->first();
                $amount = null;
                if ($milkData == null) {
                    $snfFat = null;
                } else {
                    $snfFat = $snfFats->where('user_id', $farmer->id)->first();
                }
                $last = $i == count($farmers) - 1;
            @endphp
            <tr id="milk-{{ $farmer->no }}">
                <td>{{ $farmer->no }}</td>
                <td>{{ $farmer->name }}</td>

                <td class="">
                    <input id="milkdata-{{ $farmer->id }}" type="number" class="form-control next"
                        data-next="fat-{{ $farmer->id }}" data-id="{{ $farmer->id }}" data-value="{{ $milkData == null ? '' : $milkData->amount }}"
                        value="{{ $milkData == null ? '' : $milkData->amount }}">
                </td>
                <td>
                    <input id="fat-{{ $farmer->id }}" data-value="{{ $snfFat == null ? '' : $snfFat->fat }}" type="number"
                        class="form-control next" data-next="snf-{{ $farmer->id }}"
                        value="{{ $snfFat == null ? '' : $snfFat->fat }}">
                </td>
                <td>
                    @php
                        if (!$last) {
                            $nextFarmerID = $farmers[$i + 1]->id;
                        } else {
                            $nextFarmerID = 0;
                        }
                    @endphp
                    <input id="snf-{{ $farmer->id }}" data-id="{{ $farmer->id }}" type="number"
                        class="form-control {{ $last ? '' : 'next' }} save" data-next="milkdata-{{ $nextFarmerID }}"
                        data-value="{{ $snfFat == null ? '' : $snfFat->snf }}" value="{{ $snfFat == null ? '' : $snfFat->snf }}">
                </td>

            </tr>
        @endfor

    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th>Total</th>
            <th id="total"></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
