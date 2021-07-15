<div class="prodviwer">
    <table class="w-100 prodtable">
        <tr>
            <th>Itemno</th>
            <th>Name</th>
            <th>Rate</th>
        </tr>
        @foreach (\App\Models\Product::all() as $product)
        <tr  class="hovertr" id="prod_{{$product->id}}" data-product="{{$product->toJson()}}">
            <td>{{$product->id}}</td>
            <td>{{$product->name}}</td>
            <td>{{$product->price}}</td>
        </tr>
        @endforeach
    </table>
</div>
