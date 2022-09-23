@php
    $tot = 0;
@endphp
@foreach ($sells as $sell)
<tr id="sell-{{$sell->id}}">
    <td>{{ $sell->name }}</td>
    <td>{{ $sell->title }}</td>
    <td>{{ $sell->rate }}</td>
    <td>{{ $sell->qty}}</td>
    <td class="sell-total" data-total="{{ $sell->total }}">{{ $sell->total }}</td>
    <td><span onclick="deleteSell({{ $sell->id }});" class="btn btn-danger btn-sm">Delete</span></td>
</tr>
@php
    $tot += $sell->total;
@endphp
@endforeach
<tr>
    <td colspan="4" class="text-right">Total</td>
    <td colspan="2" id="tot">{{$tot}}</td>

</tr>
