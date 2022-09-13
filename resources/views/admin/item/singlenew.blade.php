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
    @php
        $variantPer=auth_has_per('03.10');
        $editPer=auth_has_per('03.02');
        $stockPer=auth_has_per('03.05');
        $delPer=auth_has_per('03.03');
    @endphp
    <td>
        @if ($variantPer)
            <a href="{{route('admin.item.variants.index',['item'=>$item->id])}}" class="btn btn-primary">Variants</a>
            <a href="{{route('admin.item.categories.index',['item'=>$item->id])}}" class="btn btn-primary">Categories</a>
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
