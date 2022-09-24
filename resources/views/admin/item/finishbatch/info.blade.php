<div >
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
                        <th>
                            {{$batch->amount}}
                        </th>
                    </tr>
                @endforeach
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
            </table>
        </div>
    </div>
</div>