@php
$i = 1;
@endphp
@foreach ($items as $item)
    <tr id='row-"{{ $i }}"'>
        <td><input class='billitems' type='hidden' name='billitems[]' value='{{json_encode($item)}}' /> {{$i}}</td>
        <td>{{$item->name}}</td>
        <td>{{$item->rate}}</td>
        <td>{{$item->qty}}</td>
        <td>{{$item->total}}</td>
        <td></td>
        {{-- <td><span class='btn btn-danger btn-sm' onclick='removeProductItem({{$i}})'>Remove</span></td> --}}
    </tr>
    @php
        $i+=1;
    @endphp
@endforeach
