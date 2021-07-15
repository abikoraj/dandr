@foreach($advs as $adv)
@if($adv->user->role == 1)
<tr id="advance-{{$adv->id}}" data-name="{{ $adv->name }}" class="searchable">
    <td>{{ $adv->user->no }}</td>
    <td>{{ $adv->user->name }}</td>
    <td>{{ $adv->amount }} </td>
    <td>
        <button  type="button" data-advance="{{$adv->toJson()}}" class="btn btn-primary btn-sm editfarmer" onclick="initEdit(this);" >Edit</button>

        <button class="btn btn-danger btn-sm" onclick="removeData({{$adv->id}});">Delete</button></td>
</tr>
@endif
@endforeach
