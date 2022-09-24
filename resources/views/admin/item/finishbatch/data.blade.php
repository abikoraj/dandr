<div>
    <input type="checkbox" name="multi_batch" id="multi_batch" onchange="checkMultiBatch(this)" >
    <label for="multi_batch">
        Multiple Batch
    </label>
</div>
<hr>
<div class="row">

    <div class="col-md-3">
        <label id="batch_id_label" for="batch_id"> Batch</label>
        <select name="batch_id" id="batch_id" class="form-control ms">

        </select>
    </div>
    <div class="col-md-3 to_batch_id_holder d-none" >
        <label for="to_batch_id">To Batch</label>
        <select name="to_batch_id" id="to_batch_id" class="form-control ms">

        </select>
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button class="btn btn-primary" onclick="loadInfo()">
            Load Info
        </button>
    </div>
    
</div>
<hr>
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
           </th>
           <td>
            {{$finishedBatch->fresh_qty}}
           </td>
           <td>
            {{$finishedBatch->sold_qty}}
           </td>
        </tr>
    @endforeach
</table>
