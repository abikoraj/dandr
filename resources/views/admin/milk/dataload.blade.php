@foreach($milkdatas as $d)
<tr  id="milk-{{$d->no}}" data-m_amount="{{ $d->m_amount??0 }}" data-e_amount="{{ $d->e_amount??0 }}">
    <td>{{ $d->no }}</td>
    <td>{{$d->name}}</td>
    <td id="m_milk-{{$d->no}}" class="m_milk" data-value="{{ $d->m_amount??0}}" >{{ $d->m_amount??0}}</td>
    <td id="e_milk-{{$d->no}}"  class="e_milk" data-value="{{ $d->e_amount??0}}">{{ $d->e_amount??0 }}</td>
</tr>
@endforeach
