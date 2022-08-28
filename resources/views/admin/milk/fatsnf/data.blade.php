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
        @foreach ($farmers as $farmer)
            @php
                $milkData = $milkDatas->where('user_id', $farmer->id)->first();
                $snfFat = $snfFats->where('user_id', $farmer->id)->first();
            @endphp
            @if ($milkData == null)
            @else
                <tr id="milk-{{ $farmer->no }}" data-milkdata_id="{{$milkData->id}}" data-snffat_id="{{$snfFat==null?null:$snfFat->id}}">
                    <td>{{ $farmer->no }}</td>
                    <td>{{ $farmer->name }}</td>
                    <td  class="milkdata">
                        {{ $milkData->amount }}
                    </td>
                    @if ($snfFat == null)
                        <td></td>
                        <td></td>
                    @else
                        <td>{{ $snfFat->fat }}</td>
                        <td>{{ $snfFat->snf }}</td>
                    @endif
                    <td>
                        <button class="btn btn-danger" onclick="del({{$farmer->no}})">Delete</button>
                    </td>
                </tr>
            @endif
        @endforeach
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
