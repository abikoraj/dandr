<div class="row">
    <div class="col-md-6">
        <div>
            @if (env('large', false))
                <div class="row">
                    <div class="col-5 pr-1">
                        <input type="text" oninput="loadItems()" class="form-control" id="keyword" placeholder="Search Items">
                    </div>
                    <div class="col-5 px-1">
                        <input type="text" class="form-control" id="category" placeholder="Search Category">
                    </div>
                    <div class="col-2 pl-1">
                        <button class="btn btn-primary" onclick="loadItems();">Load</button>
                    </div>
                </div>
            @endif

            <div class="items">
                <table class="table table-bordered table-striped ">
                    <tr>
                        <th>
                            Name
                        </th>
                        <td></td>
                    </tr>
                    <tbody id="item-list">

                        @foreach ($items as $item)
                            @include('admin.offer.detail.singleitem',['item'=>$item])
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-3 shadow" id="offer-item">
            <form action="" class="offer-item-form">
                <div class="form-group">
                    <label for="item">{{$offer->type==2?"Buy ":" "}} Item</label>
                    <input type="text" class="form-control" id="item-name">
                    <input type="hidden" id="item-id" name="item_id">
                </div>
                @if ($offer->type == 0)
                    <div id="type-0">
                        <div class="form-group">
                            <label for="discount">Discount Amount</label>
                            <input type="number" class="form-control" id="discount" min="0" step="0.00" required>
                        </div>
                    </div>
                @elseif($offer->type==1)
                <div id="type-1">
                    <div class="form-group">
                        <label for="percentage">Discount Percentage</label>
                        <input type="number" class="form-control" id="percentage" min="0" step="0.00" max="100" required>
                    </div>
                </div>
                @elseif($offer->type==2)
                <div>
                <div id="type-2">
                    <div class="form-group">
                        <label for="percentage">Discount Percentage</label>
                        <input type="number" class="form-control" id="percentage" min="0" step="0.00" max="100" required>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="min">Minimum Quantity</label>
                            <input type="number" class="form-control" id="min" min="0" step="0.00" value="0" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="max">Maximum Quantity</label>
                            <input type="number" class="form-control" id="max" min="0" step="0.00" value="0" required>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@section('js2')
    <script>
        var item1 = -1;
        var item2 = -1;
        var type = {{ $offer->type }};
        var step=0;
        
        function select(id,name){
            $('#item-name').val(name);
            $('#item-id').val(id);
            $('#offer-item').show();
        }
        $('#offer-item').hide();

        function loadItems(){
            const _keyword=$('#keyword').val();
            if(_keyword.length>2){

                axios.post('{{route('admin.offers.get-items')}}',{"step":step,"keyword":_keyword})
                .then((res)=>{
                    $('#item-list').html(res.data);
                })
            }
        }
    </script>
@endsection
