

    <tr id="sell-{{$sell->id}}"   data-name="{{ $sell->name }}" class="searchable ">
        <td>{{ $sell->name }}</td>
        <td>{{ $sell->title }}</td>
        <td>{{ $sell->rate }}</td>
        <td>{{ $sell->qty }}</td>
        <td>{{ $sell->total }}</td>
        <td>{{ $sell->paid }}</td>
        <td>{{ $sell->due }}</td>
        <td>
            <button class="btn btn-danger btn-sm" onclick="removeData({{$sell->id}});">Delete</button></td>
    </tr>

