<tr id="center-{{ $center->id }}">
    <form action="#" id="collectionForm-{{ $center->id }}">
    @csrf
        <input type="hidden" name="id" value="{{ $center->id }}" form="collectionForm-{{ $center->id }}">
        <td>{{ $center->id }}</td>
        <td><input type="text" value="{{ $center->name }}" id="name" class="form-control" name="name" form="collectionForm-{{ $center->id }}"></td>
        <td><input style="min-width:200px;" type="text" value="{{ $center->addresss }}" id="address" class="form-control" name="address" form="collectionForm-{{ $center->id }}"></td>
        @if (env('use_milk',false))

        <td><input type="number" value="{{ $center->fat_rate }}" id="fat-rate" step="0.001" class="form-control" name="fat_rate" form="collectionForm-{{ $center->id }}"></td>
        <td><input type="number" value="{{ $center->snf_rate }}" id="snf-rate" step="0.001" class="form-control" name="snf_rate" form="collectionForm-{{ $center->id }}"></td>
        @if (env('hasextra',0)==1)
            <td>
                <input type="number" value="{{ $center->bonus }}" id="bonus" step="0.001" class="form-control" name="bonus" form="collectionForm-{{ $center->id }}">
            </td>
        @endif
        @if (env('usetc',0)==1)
        <td>
            <input type="checkbox" name="show_ts" value="1" {{$center->show_ts?'checked':''}}>
        </td>
            <td>
                <input type="number" value="{{ $center->tc }}" id="tc" step="0.001" class="form-control" name="tc" form="collectionForm-{{ $center->id }}">
            </td>
        @endif
        @if (env('usecc',0)==1)
            <td>
                <input type="checkbox" name="show_cc" value="1" {{$center->show_cc?'checked':''}}>
            </td>
            <td>
                <input type="number" value="{{ $center->cc }}" id="cc" step="0.001" class="form-control" name="cc" form="collectionForm-{{ $center->id }}">
            </td>
        @endif
        @if (env('useprotsahan',0)==1)
            <td>
                <input type="checkbox" name="use_protsahan" value="1" {{$center->use_protsahan?'checked':''}}>
            </td>
        
        @endif
        @if (env('usetransportamount',0)==1)
        <td>
            <input type="checkbox" name="use_transport" value="1" {{$center->use_transport?'checked':''}}>
        </td>
    
    @endif
        
    @endif
        <td><span onclick="editCollection({{$center->id}});" form="collectionForm-{{ $center->id }}" class="btn btn-primary btn-sm"> Update </span> <br> <span class="btn btn-danger btn-sm" onclick="removeCenter({{$center->id}});">Delete</span></td>
    </form>
</tr>
