<tr id="itemsell-{{ $sell_item->id }}" data-id="{{ $sell_item->user->no }}" data-item_number="{{ $sell_item->item->number }}">
    <td><input type="checkbox" class="ids" value="{{$sell_item->id}}"> {{$sell_item->user->no}}</td>
    <td>{{$sell_item->item->title}}</td>
    <td>{{$sell_item->rate}}</td>
    <td>{{$sell_item->qty}}</td>
    <td>{{$sell_item->total}}</td>
    <td>{{$sell_item->paid}}</td>
    <td>{{$sell_item->due}}</td>
    <td>
        <button class="badge badge-danger" onclick="removeData({{$sell_item->id}});">Delete</button>
    </td>
</tr>
