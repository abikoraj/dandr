<tr id="milkdata-{{$milkData->id}}" data-name="{{$milkData->name}}">
    <td>
        {{$milkData->name}}
    </td>
    <td>
       
        <input type="number" name="snf" id="snf-{{$milkData->snf}}" step="0.001" min="0.001" value="{{$milkData->snf}}"
        placeholder="Milk in liter" class="w-100 ">
        
    </td>
    <td>
       
        <input type="number" name="fat" id="fat-{{$milkData->fat}}" step="0.001" min="0.001" value="{{$milkData->fat}}"
        placeholder="Milk in liter" class="w-100 ">
        
    </td>
    
    <td>
        <button class="btn btn-sm btn-primary" onclick="update({{$milkData->id}})">Update</button>
        <button class="btn btn-sm btn-danger" onclick="del({{$milkData->id}})">Delete</button>
    </td>
</tr>