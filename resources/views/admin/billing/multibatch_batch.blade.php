<span class="d-none" id="connected_batches">
    {!! json_encode($batches) !!}
</span>
<hr>
Batch From: {{ $targetItem->title }}
<hr>
<div class="row">
    <input type="hidden" name="target_item_id" id="target_item_id" value="{{ $targetItem->id }}">
    <input type="hidden" name="item_id" id="item_id" value="{{ $item->id }}">

    <div class="col-md-3">
        <label for="connected_batch_id">From Batch</label>
        <select class="form-control" name="connected_batch_id" id="connected_batch_id">
            @foreach ($batches as $batch)
                <option value="{{ $batch->batch_id }}">{{ $batch->batch_no }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label for="connected_to_batch_id">To Batch</label>
        <select class="form-control" name="connected_to_batch_id" id="connected_to_batch_id">
            @foreach ($batches as $batch)
                <option value="{{ $batch->batch_id }}">{{ $batch->batch_no }}</option>
            @endforeach
        </select>
    </div>
    @if (count($cats) > 0)
        <div class="col-md-3">
            <label for="connected_item_category_id">Category</label>
            <select class="form-control" name="connected_item_category_id" id="connected_item_category_id"  onchange="connected_item_category_changed(this)">
                @foreach ($cats as $cat)
                    <option data-rate="{{$cat->price}}" value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
    @endif
    <div class="col-md-3">
        <label for="connected_rate">Rate</label>
        <input type="number" name="connected_rate" id="connected_rate" class="form-control" min="0"
            step="0.000" value="{{$rate}}">
    </div>
    <div class="col-md-3">
        <label for="connected_qty">Qty</label>
        <input type="number" name="connected_qty" id="connected_qty" class="form-control" min="0"
            step="0.000">
    </div>
    <div class="col-md-3 mt-2 d-flex align-items-end" >
        <button class="btn btn-primary w-100" onclick="connected_addToBill()">
            Add Bill Item
        </button>
    </div>
</div>
