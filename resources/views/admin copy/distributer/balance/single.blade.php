<tr id="ledger-{{$d->id}}">
    <td>
        {{$d->user->distributer()->id}}
    </td>
    <td>
        {{$d->user->name}}
    </td>
    <td>
        {{(float)$d->amount}} {{$d->type==1?"CR":"DR"}}
    </td>
    <td>
        <button class="btn btn-primary" onclick="initEditLedger('Edit Account Opening Amount',{{$d->id}});">Edit
        @if ($d->user->ledgers->count()<=1)
            <button class="btn btn-danger" onclick="deleteLedger({{$d->id}},removeLedger);">Delete
        @else

        @endif
    </td>

</tr>

