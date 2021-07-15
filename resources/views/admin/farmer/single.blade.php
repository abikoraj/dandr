<tr id="farmer-{{$user->id}}" data-no="{{ $user->no }}" data-name="{{ $user->name }}" class="searchable">
    <td>{{ $user->no }}</td>
    <td>{{ $user->name }}</td>
    @if(env('requirephone',1)==1)
        <td>{{ $user->phone }}</td>
    @endif
    <td>{{ $user->address }} </td>
    <td>
        @if($user->amount > 0)
            {{ $user->amount}}   ( {{$user->amounttype==1?"Cr":"Dr"}} )
        @else
            --
        @endif
    </td>
    <td>
        <button  type="button" data-farmer="{{$user->toJson()}}" class="btn btn-primary btn-sm editfarmer" onclick="initEdit(this);" >Edit</button>
        |
        <a href="{{ route('admin.farmer.detail',$user->id) }}" class="btn btn-primary btn-sm">View</a> |
        <button class="btn btn-danger btn-sm" onclick="removeData({{$user->id}});">Delete</button></td>
</tr>
