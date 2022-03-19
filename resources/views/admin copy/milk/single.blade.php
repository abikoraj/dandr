<tr id="milk-{{$d->no}}" data-m_amount="{{ $d->m_amount??0 }}" data-e_amount="{{ $d->e_amount??0 }}">
    <td>{{ $d->no }}</td>
    <td id="m_milk-{{$d->no}}"  >{{ $d->m_amount??0 }}</td>
    <td id="e_milk-{{$d->no}}" >{{ $d->e_amount??0 }}</td>
</tr>
