
        <tr id="sell-{{$sell->id}}">
            <td>{{ $user->name }}</td>
            <td>{{ $name }}</td>
            <td>{{ $sell->rate }}</td>
            <td>{{ $sell->qty }}</td>
            <td class="sell-total" data-total="{{ $sell->rate*$sell->qty }}">{{ $sell->rate * $sell->qty }}</td>
            <td><span onclick="deleteSell({{ $sell->id }});" class="btn btn-danger btn-sm">Delete</span></td>
        </tr>
