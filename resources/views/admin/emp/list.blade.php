
@foreach($emps as $k => $emp)
<tr id="employee-{{$emp->user->id}}" data-name="{{ $emp->user->name }}" class="searchable">
    <td>{{ $emp->user->name }}</td>
    <td>{{ $emp->user->phone }}</td>
    <td>{{ $emp->user->address }}</td>
    <td>{{ $emp->salary??'--' }}</td>
    <td>
        <button  type="button" data-employee="{{$emp->user->toJson()}}" data-acc="{{ $emp->acc??'--' }}" data-salary="{{ $emp->salary??'--' }}" class="btn btn-primary btn-sm" onclick="initEdit(this);" >Edit</button>
        |
        <a href="{{ route('admin.employee.detail',$emp->user->id) }}" class="btn btn-primary btn-sm" target="_blank">View</a>
        |
        <button class="btn btn-danger btn-sm" onclick="removeData({{$emp->user->id}});">Delete</button></td>
</tr>
@endforeach
