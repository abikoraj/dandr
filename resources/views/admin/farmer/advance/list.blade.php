@foreach($advs as $adv)
<tr id="advance-{{$adv->id}}" data-name="{{ $adv->name }}" class="searchable">
    <td>{{ $adv->no }}</td>
    <td>{{ $adv->name }}</td>
    <td>{{ $adv->amount }} </td>
    <td>
        <button  type="button" data-advance="{{$adv->toJson()}}" class="btn btn-primary btn-sm editfarmer" onclick="initEdit(this);" >Edit</button>

        <button class="btn btn-danger btn-sm" onclick="removeData({{$adv->id}});">Delete</button></td>
</tr>
@endforeach
