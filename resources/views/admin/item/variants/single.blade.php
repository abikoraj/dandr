<div class="p-3 mb-3 shadow">
    <form action="{{route('admin.item.variants.update',['id'=>$variant->id])}}"  method="post">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    @php
                        $unitName=env('multi_package',false)?'conversion_id':'unit';
                    @endphp
                    <label for="{{$unitName}}">Unit</label>
                    @if(env('multi_package',false))
                    <input type="text"  id="unit" readonly value="{{$variant->unit}}" class="form-control">
                    @else
                    <input type="text" name="unit" class="form-control" id="unit" required value="{{$variant->unit}}" >
                    @endif
                </div>
            </div>
            @if (!env('multi_stock',false))
            <div class="col-md-3">
                <div class="form-group">
                    <label for="wholesale">Wholesale</label>
                    <input type="number" name="wholesale" id="wholesale" class="form-control" value="{{$variant->wholesale}}" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" name="price" id="price" class="form-control" value="{{$variant->price}}"  required>
                </div>
            </div>
            @else
            <div class="col-12">
            <table class="table">
                <tr>
                    <th>Center</th>
                    <th>
                        Wholesale
                    </th>
                    <th>
                        Price
                    </th>
                </tr>
                @foreach ($centers as $center)
                    @php
                        $variant_price=$variant_prices->where('center_id',$center->id)->where('item_variant_id',$variant->id)->first();

                    @endphp
                        <tr>
                            <td>{{$center->name}} <input type="hidden" name="centers[]" value="{{$center->id}}"> </td>
                            <td> <input type="number" name="wholesale_{{$center->id}}" id="wholesale_{{$center->id}}" value="{{$variant_price?$variant_price->wholesale:''}}" class="form-control"> </td>
                            <td> <input type="number" name="price_{{$center->id}}" id="price_{{$center->id}}"  value="{{$variant_price?$variant_price->wholesale:''}}" class="form-control"> </td>
                        </tr>
                    @endforeach
            </table>
            </div>
            @endif
            <div class="col-12 pt-2">
                <button class="btn btn-primary">Update Variant</button>
                <a href="{{route('admin.item.variants.del',['id'=>$variant->id])}}" class="btn btn-danger" onclick="return prompt('Enter yes to continue')=='yes';">Delete</a>
            </div>
        </div>
    </form>

</div>
