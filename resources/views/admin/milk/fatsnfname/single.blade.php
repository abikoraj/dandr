<tr id="milk-{{ $farmer->no }}" data-milkdata_id="{{$milkData->id}}" data-snffat_id="{{$snfFat==null?null:$snfFat->id}}">
    <td>{{ $farmer->no }}</td>
    <td>{{ $farmer->name }}</td>
    <td class="milkdata" >
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