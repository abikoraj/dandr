@foreach($distributer as $user)
    <tr id="distributer-{{$user->id}}" data-name="{{ $user->name }}" class="searchable">
        <td>{{ $user->dis_id }}</td>
        <td>{{ $user->name }}</td>
        <td>{{ $user->phone }}</td>
        <td>{{ $user->address }}</td>
        {{-- <td>{{ $user->distributer()->rate }}</td>
        <td>{{ $user->distributer()->amount }}</td> --}}
        <td>
            <button type="button" data-distributer="{{$user->toJson()}}" data-days="{{ $user->distributer()->credit_days }}" data-limit="{{ $user->distributer()->credit_limit }}"  class="btn btn-primary btn-sm" onclick="initEdit(this);">Edit</button>
            |
            <a href="{{ route('admin.distributer.detail',$user->id) }}" class="btn btn-primary btn-sm">View</a>
            |
            <button class="btn btn-danger btn-sm" onclick="removeData({{$user->id}});">Delete</button></td>
    </tr>
@endforeach
