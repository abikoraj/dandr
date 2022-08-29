<tr id="sell_item_{{$sell_item->id}}">
    <td>
        {{$sell_item->name}}
    </td>
    <td>
        {{$sell_item->item}}
    </td>
    <td>
        {{$sell_item->qty}}
    </td>
    <td>
        {{$sell_item->rate}}
    </td>
    <td>
        {{$sell_item->total}}
    </td>
    <td>
        {{$sell_item->paid}}
    </td>
    <td>
        {{$sell_item->due}}
    </td>
    <td>
        <button class="btn btn-danger" onclick="del({{$sell_item->id}})"> Del</button>
    </td>
</tr>