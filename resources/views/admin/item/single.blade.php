<tr id="item-{{ $item->id }}" data-name="{{ $item->title }}">
    <td>{{$item->title}}</td>
    <td>{{$item->number}}</td>
    <td>{{$item->sell_price}}</td>
    <td>{{$item->stock}}</td>
    <td>{{$item->unit}}</td>
    {{-- <td>{{$item->reward_percentage}}</td> --}}
    <td>
        @if (auth_has_per('03.02'))
        <button  class="btn btn-primary btn-sm"  onclick="initEdit({{$item->id}});" >Edit</button>
        @endif
        @if(env('multi_stock',false))
        @if (auth_has_per('03.05'))
            <a href="{{route('admin.item.center-stock',['id'=>$item->id])}}" class="btn btn-primary btn-sm"  >Stock</a>
        @endif
        @endif
        @if (auth_has_per('03.03'))
        <button class="btn btn-danger btn-sm" onclick="removeData({{$item->id}});">Delete</button>
        @endif
    </td>
</tr>
