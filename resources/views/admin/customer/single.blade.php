<tr id="customer_{{$customer->id}}" >
    <td>
        {{$customer->id}}
    </td>
    <td>
        {{$customer->name}}
    </td>
    <td>
        {{$customer->phone}}
    </td>
    <td>
        {{$customer->address}}
    </td>
    <td>
        {{$customer->panvat??'--'}}
    </td>
  
    <td>
        <button class="btn btn-sm btn-primary" data-info="{{$customer->basicInfo()}}" onclick="initEdit(this)">Edit</button>
        <a  target="_blank" href="{{ route("admin.customer.detail",['id'=>$customer->user_id]) }}" class="btn btn-sm btn-secondary">Detail</a>
        <button class="btn btn-sm btn-danger">Del</button>
    </td>
    
</tr>