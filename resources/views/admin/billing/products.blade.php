<div class="prodviwer">
    @php
        $products=\App\Models\Item::where('posonly',1)->get(['id','number','title','sell_price','unit']);
    @endphp
    <table class="w-100 prodtable">
        <tr>
            <th>Itemno</th>
            <th>Name</th>
            <th>Rate</th>
        </tr>
        @foreach ($products as $product)
        <tr  class="hovertr" id="prod_{{$product->number}}" data-product="{{$product->toJson()}}" onclick="selectProduct(this)">
            <td>{{$product->number}}</td>
            <td>{{$product->title}}</td>
            <td>{{$product->sell_price}}</td>
        </tr>
        @endforeach
    </table>

    <datalist id="product-list">
        @foreach ($products as $product)
            <option value="{{$product->number}}">{{$product->title}}</option>
        @endforeach
    </datalist>
</div>


