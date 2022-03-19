@foreach($milkdatas as $d)
@if ($d->user()!=null)

    <tr  id="milk-{{$d->user()->no}}" data-m_amount="{{ $d->m_amount??0 }}" data-e_amount="{{ $d->e_amount??0 }}">
        <td>{{ $d->user()->no }}</td>
        <td id="m_milk-{{$d->user()->no}}"  >{{ $d->m_amount??0}}</td>
        <td id="e_milk-{{$d->user()->no}}" >{{ $d->e_amount??0 }}</td>
    </tr>
@endif
@endforeach
