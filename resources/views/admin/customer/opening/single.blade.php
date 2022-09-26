<tr id="opening-{{$opening->id}}">
    <td>
        {{_nepalidate($opening->date)}}
    </td>
    <td>
        {{$opening->name}}
    </td>
    <td>
        {{$opening->amount}} {{$opening->type==1?'Cr.':'Dr.'}}
    </td>
    <td>
        <button class="btn btn-primary"  onclick="initEditLedger('Edit Account Opening Amount',{{$opening->id}});" >Edit</button>
        @if (canDelOpening($opening->user_id))
            <button class="btn btn-danger" onclick="del({{$opening->id}})">Delete</button>
        @endif
    </td>
</tr>