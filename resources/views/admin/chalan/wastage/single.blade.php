<tr id="wastage-{{$data->id}}">
    <td>{{ $data->title }}</td>
    <td>{{ $data->wastage }}</td>
    <td><span onclick="wastageDelete({{$data->id}});" class="btn btn-danger btn-sm">Del</span></td>
</tr>
