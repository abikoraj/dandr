<form action="{{route('admin.milk.chalan-save')}}" onsubmit="return save(event,this);">
    @csrf
    <input type="hidden" name="date" value="{{$date}}">
    <hr>
    Main Center - {{$maincenter->name}}
    <hr>
    @foreach ($centers as $center)
        <dl>
            <dt>{{$center->name}}</dt>
            <dd>
                @if (count($center->chalans)>0)
                @foreach ($center->chalans as $chalan)
                    
                @endforeach
                <input type="hidden" name="chalan_ids[]" value="{{$chalan->stock_out_item_id}}">
                Milk Amount <input type="number" min="0" name="chalan_amount_{{$chalan->stock_out_item_id}}" value="{{$chalan->amount}}" required> 
                @else
                <input type="hidden" name="center_ids[]" value="{{$center->id}}">
                Milk Amount <input type="number" min="0" name="center_amount_{{$center->id}}" > 
                @endif
            </dd>
        </dl>
    @endforeach
    <hr>
    <button class="btn btn-primary">Save Chalan</button>
</form>