<table class="w-100">
    <th class="text-right pr-2">
        Status
    </th>
    <td>
        {{counterStatus($status->status)}}
    </td>
    @if (env('use_opening',false))
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
    @endif
    <tr>
        <th class="text-right pr-2">
            Current
        </th>
        <td>
            {{$status->current}}
        </td>
    </tr>

    @if (env('use_opening',false))
        <tr>
            <th class="text-right pr-2">
                Closing
            </th>
            <td>
                {{$status->status==3? $status->closing:'--'}}
            </td>
        </tr>
    @endif
    <tr>

        <td colspan="2" class=" px-1">
            <button class="btn btn-primary w-100" onclick="refreshCounter(' {{ route('admin.counter.status.get',['counter'=>$status->counter_id])}}',{{$status->counter_id}})">
                Refresh
            </button>
            @if ($status->status==3 && env('use_opening',false))
                <button class="btn btn-success w-100" onclick="reopenCounter(' {{ route('admin.counter.day.reopen',['id'=>$status->id])}}',{{$status->counter_id}})">
                    Reopen Counter
                </button>
            @endif
        </td>
    </tr>
</table>
