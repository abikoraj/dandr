<form action="{{route('admin.milk.chalan-save')}}" onsubmit="return save(event,this);">
    @csrf
    <input type="hidden" name="date" value="{{$date}}">
    <hr>
    Main Center - {{$maincenter->name}} <span class="btn btn-succes" onclick="loadAllChalan()">Load All Chalan</span>
    <hr>
    @foreach ($centers as $center)
        <dl>
            <dt>{{$center->name}}</dt>
            <dd>
                @if (count($center->chalans)>0)
                @foreach ($center->chalans as $chalan)
                    
                @endforeach
                <input type="hidden" name="chalan_ids[]" value="{{$chalan->stock_out_item_id}}">
                Milk Amount <input type="number" min="0"  id="amount_{{$center->id}}" step="0.01"  name="chalan_amount_{{$chalan->stock_out_item_id}}" value="{{$chalan->amount}}" required> 
                @else
                <input type="hidden" name="center_ids[]" value="{{$center->id}}">
                Milk Amount <input   id="amount_{{$center->id}}" type="number" min="0" step="0.01" name="center_amount_{{$center->id}}" > 

                @endif 

                <span class="btn btn-success milkdata" id="collection_{{$center->id}}" data-amount="{{$center->milktotal}}" onclick="collect({{$center->id}},{{$center->milktotal}});">
                    {{$center->milktotal}}
                </span>
            </dd>

        </dl>
    @endforeach
    <hr>
    <button class="btn btn-primary">Save Chalan</button>
</form>