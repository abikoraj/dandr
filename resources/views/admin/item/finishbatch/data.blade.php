<div class="row">
    <div class="col-md-3">
        <label for="batch_id">Batch</label>
        <select name="batch_id" id="batch_id" class="form-control ms">

        </select>
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button class="btn btn-primary">
            Mark Finish Batch
        </button>
    </div>
</div>
<hr>
<table class="table table-bordered">
    <tr>
       
        <th>
            Batch no
        </th>
        <th>
            Produced
        </th>
        <th>
            Sold / Used
        </th>
        <th>
            Loss in weight
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
           <td>
            {{$finishedBatch->loss_qty}}
           </td>
        </tr>
    @endforeach
</table>
