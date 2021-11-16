<tr id="distributer-{{$user->id}}" data-name="{{ $user->name }}" class="searchable">
    <td>{{ $user->distributer()->id }}</td>
    <td>{{ $user->name }}</td>
    <td>{{ $user->phone }}</td>
    <td>{{ $user->address }}</td>
    {{-- <td>{{ $user->distributer()->rate }}</td>
    <td>{{ $user->distributer()->amount }}</td> --}}
    <td>
        @if (auth_has_per('04.03'))

        <button type="button" data-distributer="{{$user->toJson()}}" class="btn btn-primary btn-sm" onclick="initEdit(this);">Edit</button>
        |
        @endif
        @if (auth_has_per('04.11'))
        <a href="{{ route('admin.distributer.detail',$user->id) }}" class="btn btn-primary btn-sm">View</a>
        |
        @endif
        @if (auth_has_per('04.04'))

        <button class="btn btn-danger btn-sm" onclick="removeData({{$user->id}});">Delete</button></td>
        @endif
</tr>
{{-- dfdfdf --}}
