
<tr id="snffat_{{$snffat->id}}" data-snf="{{ $snffat->snf??0 }}" data-fat="{{ $snffat->fat??0 }}">
    <td>{{ $snffat->no }}</td>
    <td id="{{$snffat->id}}_fat" >{{ $snffat->fat??0 }}</td>
    <td id="{{$snffat->id}}_snf" >{{ $snffat->snf??0 }}</td>
    <td>
        <button class="btn btn-primary" data-snffat="{{$snffat->toJson()}}" onclick="showSnfFatUpdate(this)">
            Edit
        </button>
        <button class="btn btn-danger" data-snffat="{{$snffat->toJson()}}" onclick="delSnfFat(this);">
            delete
        </button>
    </td>
</tr>
