@foreach($data as $d)
<tr id="snffat_{{$d->id}}" data-snf="{{ $d->snf??0 }}" data-fat="{{ $d->fat??0 }}">
    <td>{{ $d->user()->no }}</td>
    <td id="{{$d->id}}_fat" >{{ $d->fat??0 }}</td>
    <td id="{{$d->id}}_snf" >{{ $d->snf??0 }}</td>
    <td class="d-print-none">
        <button class="btn btn-primary" data-snffat="{{$d->toJson()}}" onclick="showSnfFatUpdate(this)">
            Edit
        </button>
        <button class="btn btn-danger" data-snffat="{{$d->toJson()}}" onclick="delSnfFat(this);">
            delete
        </button>
    </td>
</tr>
    {{-- @include('admin.snf.single',['snffat'=>$d]) --}}
@endforeach
