@foreach($sell as $itemsell)
@if($itemsell->user!=null)
<tr id="itemsell-{{ $itemsell->id }}" data-id="{{ $itemsell->user->no }}" data-item_number="{{ $itemsell->item->number }}">
    <td><input type="checkbox" class="ids" value="{{$itemsell->id}}"> {{$itemsell->user->no}}</td>
    <td>{{$itemsell->item->title}}</td>
    <td>{{$itemsell->rate}}</td>
    <td>{{$itemsell->qty}}</td>
    <td>{{$itemsell->total}}</td>
    <td>{{$itemsell->paid}}</td>
    <td>{{$itemsell->due}}</td>
    <td>
        <button class="badge badge-danger" onclick="removeData({{$itemsell->id}});">Delete</button>
    </td>
</tr>
@endif
@endforeach
