<tr id="expense-{{$exp->id}}" data-name="{{ $exp->title }}" class="searchable">
    <td>{{ $exp->title }}</td>
    <td>{{ _nepalidate($exp->date) }}</td>
    <td>{{ $exp->payment_by }}</td>
    <td>{{ $exp->amount }}</td>
    <td>{{ $exp->payment_detail }}</td>
    <td>{{ $exp->remark }}</td>
    <td>
        @if (auth_has_per('06.07'))
        <button  type="button"  class="btn btn-primary btn-sm" onclick="initEdit('{{$exp->title}}',{{$exp->id}});" >Edit</button>
        @endif
        |
        @if (auth_has_per('06.08'))
        <button class="btn btn-danger btn-sm" onclick="removeData({{$exp->id}});">Delete</button></td>
        @endif
</tr>

{{-- dfhsjfhskjhfsdkjhfjk --}}
