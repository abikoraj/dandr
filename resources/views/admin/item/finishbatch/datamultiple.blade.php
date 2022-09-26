<div class="row">
    <div class="col-md-6">
        <label for="multiple_batch_id">Remaning Batch</label>
        <select name="multiple_batch_id" id="multiple_batch_id" class="form-control">
            @foreach ($bill_items as $bill_item)
                <option data-batch_id="{{$bill_item->batches[0]}}" data-to_batch_id="{{$bill_item->batches[1]}}"  value="{{$bill_item->combo}}">
                    {{$bill_item->batch}}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button class="btn btn-primary" onclick="loadInfo()">
            Load Info
        </button>
    </div>
</div>
<div id="info">

</div>
<hr>
<table class="table table-bordered">
    <tr>
       
        <th>
            Batch no
        </th>
        <th>
            Produced QTY
        </th>
        <th>
            Sold / Used QTY
        </th>
        
    </tr>
    @foreach ($finishedBatches as $finishedBatch)
        <tr>
           <th>
            {{$manufactured_items->where('id',$finishedBatch->batch_id)->first()->batch_no}}
            @if ($finishedBatch->multi==1)
                - 
                {{$manufactured_items->where('id',$finishedBatch->to_batch_id)->first()->batch_no}}
            @endif
           </th>
           <td>
            {{$finishedBatch->fresh_qty}}
           </td>
           <td>
            {{$finishedBatch->sold_qty}}
           </td>
        </tr>
    @endforeach
    <tr>
        <th >
            Total
        </th>
        <th>
            {{{$finishedBatches->sum('fresh_qty')}}}
        </th>
        <th>
            {{{$finishedBatches->sum('sold_qty')}}}
        </th>
    </tr>
</table>