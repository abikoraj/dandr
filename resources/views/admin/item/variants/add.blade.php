<div class="p-3">
    <form action="{{route('admin.item.variants.add',['id'=>$item->id])}}" onsubmit="save(this,event)">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    @php
                        $unitName=env('multi_package',false)?'conversion_id':'unit';
                    @endphp
                    <label for="{{$unitName}}">Unit</label>
                    @if(env('multi_package',false))
                    <select type="text" class="form-control" id="conversion_id" name="conversion_id" required>
                        @foreach ($units as $unit)
                            <option value="{{$unit->id}}">{{$unit->name}}</option>
                        @endforeach
                    </select>
                    @else
                    <input type="text" name="unit" class="form-control" id="unit" required>
                    @endif
                </div>
            </div>
            @if(!env('multi_package'))
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="ratio">Per unit of item</label>
                        <input type="number" step="0.00001" class="form-control" name="ratio" id="ratio">
                    </div>
                </div>
            @endif
            @if (!env('multi_stock',false))
            <div class="col-md-3">
                <div class="form-group">
                    <label for="wholesale">Wholesale</label>
                    <input type="number" name="wholesale" id="wholesale" class="form-control" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" name="price" id="price" class="form-control" required>
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
                    <tr>
                        <td>{{$center->name}} <input type="hidden" name="centers[]" value="{{$center->id}}"> </td>
                        <td> <input type="number" step="0.01" name="wholesale_{{$center->id}}" id="wholesale_{{$center->id}}" class="form-control"> </td>
                        <td> <input type="number" step="0.01" name="price_{{$center->id}}" id="price_{{$center->id}}" class="form-control"> </td>
                    </tr>
                @endforeach
            </table>
            </div>
            @endif
            <div class="col-12 pt-2">
                <button class="btn btn-primary">Add Variant</button>
                <button class="btn btn-danger" onclick="win.hide()">Cancel</button>
            </div>
        </div>
    </form>

</div>
