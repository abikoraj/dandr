<tr id="item-{{$item->id}}">
    <td>
        {{$item->title}}
    </td>
    <td class="text-right">
        <button class="btn btn-primary" onclick="select({{$item->id}},'{{$item->title}}')">
            Add -->
        </button>
        
    </td>
</tr>