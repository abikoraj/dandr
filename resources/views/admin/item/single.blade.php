<tr id="item-{{ $item->id }}" data-name="{{ $item->title }}">
    <td>{{$item->title}}</td>
    <td>{{$item->number}}</td>
    <td>{{$item->sell_price==0?'--':$item->sell_price}}</td>
    <td>{{$item->stock}}</td>
    @if (env('multi_package',false))
    <td>
        {{$item->cunit}}
    </td>
    @else
    <td>{{$item->unit}}</td>
    @endif
    {{-- <td>{{$item->reward_percentage}}</td> --}}
    <td>
        @if ($variantPer)
            <a href="{{route('admin.item.variants.index',['item'=>$item->id])}}" class="btn btn-primary">Variants</a>
        @endif
        @if ($editPer)
        <button  class="btn btn-primary btn-sm"  onclick="initEdit({{$item->id}});" >Edit</button>
        @endif
        @if(env('multi_stock',false))
            @if ($stockPer)
                <a href="{{route('admin.item.center-stock',['id'=>$item->id])}}" class="btn btn-primary btn-sm"  >Stock</a>
            @endif
        @endif
        @if ($delPer)
        <button class="btn btn-danger btn-sm" onclick="removeData({{$item->id}});">Delete</button>
        @endif
    </td>
</tr>
