<table class="w-100">
    <th class="text-right pr-2">
        Status
    </th>
    <td>
        {{counterStatus($status->status)}}
    </td>
    <tr>
        <th class="text-right pr-2">
            Request
        </th>
        <td>
            {{$status->request}}
        </td>
    </tr>
    <tr>
        <th class="text-right pr-2">
            Opening
        </th>
        <td>
            {{$status->opening}}
        </td>
    </tr>
    <tr>
        <th class="text-right pr-2">
            Current
        </th>
        <td>
            {{$status->current}}
        </td>
    </tr>
    <tr>
        <th class="text-right pr-2">
            Closing
        </th>
        <td>
            {{$status->closing}}
        </td> 
    </tr>
    <tr>
        
        <td colspan="2" class=" px-1">
            <button class="btn btn-primary w-100" onclick="refreshCounter(' {{ route('admin.counter.status.get',['counter'=>$status->counter_id])}}',{{$status->counter_id}})">
                Refresh
            </button>
        </td>
    </tr>
</table>