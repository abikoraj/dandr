<div class="shadow p-3 mt-2">
    <div class="row">
        <div class="col-md-6">
            <h5>
                Production
            </h5>
            <table class="table table-borered">
                <thead>
                    <tr>
                        <th>
                            Batch
                        </th>
                        <th>
                             Qty
                        </th>
                    </tr>
                </thead>
                @foreach ($batches as $batch)
                    <tr>
                        <th>
                            {{$batch->batch_no}}
                        </th>
                        <td>
                            {{$batch->amount}}
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <th>
                        Total
                    </th>
                    <td>
                        {{$batches->sum('amount')}}
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <h5>
                Sales
            </h5>
            <table class="table table-borered">
                <thead>
                    <tr>
                        <th>
                            Item
                        </th>
                        <th>
                             Qty
                        </th>
                    </tr>
                </thead>
                @foreach ($bill_items as $bill_item)
                    <tr>
                        <th>
                            {{$bill_item->name}}
                        </th>
                        <th>
                            {{$bill_item->qty}}
                        </th>
                    </tr>

                @endforeach
                <tr>
                    <th>
                        Total
                    </th>
                    <td>
                        {{$bill_items->sum('qty')}}
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <hr>
    <div class="row">
        
        <div class="col-md-4">
            <strong>
                Total Production
            </strong>
            <div>
                {{$batches->sum('amount')}}
            </div>
        </div>
        <div class="col-md-4">
            <strong>
                Total Sales
            </strong>
            <div>
                {{$bill_items->sum('qty')}}
            </div>
        </div>
        <div class="col-md-4">
            <strong>
                Loss in Weight
            </strong>
            <div>
                {{$batches->sum('amount') - $bill_items->sum('qty')}}
            </div>
        </div>
        <div class="col-12">
            <hr>
        </div>
        <div class="col-md-4">
            <button class="btn btn-primary w-100" onclick="addBatchFinish()">Mark As Batch Finish</button>
        </div>

        <form action="{{route('admin.item.batch.finished.add')}}" id="addFinishedBatch">
            <input type="hidden" name="batch_id" value="{{$batch_id}}">
            <input type="hidden" name="to_batch_id" value="{{$to_batch_id}}">
            <input type="hidden" name="item_id" value="{{$item_id}}">
            <input type="hidden" name="multi" value="{{$multi}}">
            <input type="hidden" name="fresh_qty" value="{{$batches->sum('amount')}}">
            <input type="hidden" name="sold_qty" value="{{$bill_items->sum('qty')}}">
            
        </form>
        
    </div>
</div>