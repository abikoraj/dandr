@foreach($data as $d)
<tr id="snffat_{{$d->id}}" data-snf="{{ $d->snf??0 }}" data-fat="{{ $d->fat??0 }}">
    @php
        $user=$d->user()
    @endphp
    <td>{{ $user->no }}</td>
    <td>{{ $user->name }}</td>
    <td id="{{$d->id}}_fat" >{{ $d->fat??0 }}</td>
    <td id="{{$d->id}}_snf" >{{ $d->snf??0 }}</td>
    <td class="d-print-none">
        @if (auth_has_per('02.05'))

        <button class="btn btn-primary" data-snffat="{{$d->toJson()}}" onclick="showSnfFatUpdate(this)">
            Edit
        </button>
        @endif
        @if (auth_has_per('02.06'))

        <button class="btn btn-danger" data-snffat="{{$d->toJson()}}" onclick="delSnfFat(this);">
            delete
        </button>
        @endif
    </td>
</tr>
    {{-- @include('admin.snf.single',['snffat'=>$d]) --}}
@endforeach
