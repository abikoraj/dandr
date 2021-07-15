<tr id="center-{{ $product->id }}" data-name="{{ $product->name }}">
    <form action="#" id="collectionForm-{{ $product->id }}">
        <td>@csrf{{ $product->id }}</td>
        <input type="hidden" name="id" value="{{$product->id}}">
        <td><input type="text" value="{{ $product->name }}" class="form-control" name="name" form="collectionForm-{{ $product->id }}"></td>
        <td><input type="number" value="{{ $product->price }}" class="form-control" name="price" form="collectionForm-{{ $product->id }}"></td>
        <td><input type="text" value="{{ $product->unit }}" id="fatrate" step="0.001" class="form-control" name="unit" form="collectionForm-{{ $product->id }}"></td>
        <td><input type="text" value="{{ $product->stock }}" id="fatrate" step="0.001" class="form-control" name="stock" form="collectionForm-{{ $product->id }}"></td>
        <td><span onclick="update({{$product->id}});" form="collectionForm-{{ $product->id }}" class="btn btn-primary btn-sm"> Update </span> <span class="btn btn-danger btn-sm" onclick="del({{$product->id}});">Delete</span></td>
    </form>
</tr>
