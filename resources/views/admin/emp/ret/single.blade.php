<tr id="advancerow-{{$advance->id}}">
    <td>{{$advance->title}}</td>
    <td>
        {{$advance->user->name}}
    </td>
    <td>
        {{$advance->amount}}
    </td>
    <td>
        <button class="btn btn-sm btn-success" onclick="initUpdate({{$advance->id}})">Update</button>
        <button class="btn btn-sm btn-danger" onclick="del({{$advance->id}})">Delete</button>
    </td>
</tr>
