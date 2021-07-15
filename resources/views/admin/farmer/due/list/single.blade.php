<tr id="ledger-{{$ledger->id}}">
    <td>
        {{$ledger->no}}
    </td>
    <td>
        {{$ledger->name}}
    </td>
    <td>
        {{(float)$ledger->amount}} {{$ledger->type==1?"CR":"DR"}}
    </td>
    <td>
        <button onclick="initEditLedger('{{$ledger->title}}',{{$ledger->id}})">
            Edit
        </button>
        <button onclick="deleteLedger({{$ledger->id}},removeData)">
            Delete
        </button>
    </td>
</tr>


