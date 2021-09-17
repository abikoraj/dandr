<div class="row">
    <div class="col-md-6">
        <div>
            @if ($large)
                <div class="row">
                    <div class="col-5 pr-1">
                        <input type="text"  class="form-control" id="keyword" placeholder="Search Items">
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
                    <input type="hidden" id="free-item-id" name="free_item_id">
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
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="buy_amount">Buy Amount</label>
                            <input type="number" class="form-control" id="buy_amount" min="0" step="0.00" max="100" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="free_amount">Free Amount</label>
                            <input type="number" class="form-control" id="free_amount" min="0" step="0.00" max="100" required>
                        </div>
                    </div>

                    @if($large)
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="free_item">Free Item</label>
                            <input type="text" class="form-control" id="free_item" placeholder="Free Item">
                        </div>
                    </div>
                    @else
                    <div class="col-md-12">
                        <div class="col-5 pr-1">
                            <select type="text" class="form-control" id="free_item" placeholder="Free Item">
                                @foreach ($items as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

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
@if ($large)
    @include('admin.search.list')
@endif
    <script>
        var item1 = -1;
        var item2 = -1;
        var type = {{ $offer->type }};
        var step=0;
        var items=[];
        var freeitem=[];
        function select(id,name){
            $('#item-name').val(name);
            $('#item-id').val(id);
            $('#offer-item').show();
        }
        function selectFree(id,name){
            $('#free_item').val(name);
            $('#free-item-id').val(id);
            $('#free_item').clearSearch();
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

        @if($large)
        
            function filteritems (_keyword) {
                console.log(this,_keyword);
                let _list=[];
                let _index=0;
                for (let index = 0; index < this.length; index++) {
                    const element = this[index];
                    if (element.title.toLowerCase().startsWith(_keyword.toLowerCase())) {
                        _list.push(element);
                        if (_index >= 100) {
                            break;
                        }
                        _index += 1;
                    }
                }
                return _list;
            }

            function renderItem(){
                html="";
           
                this.forEach((item) => {
                    html +='<tr id="item-'+item.id+'"><td>'+item.title+'</td>'+
                    '<td class="text-right">'+
                    '<button class="btn btn-primary" onclick="select('+item.id+',\''+item.title+'\')">Add --></button></td></tr>';
                });
                return html;
            }
            function renderFreeItem(){
                html='<table ';
           
                this.forEach((item) => {
                    html+='<tr class="search-item" onclick="selectFree('+item.id+',\''+item.title+'\')">'+
                        '<td>'+item.title+'</td>'+
                    '</tr>';
                });
                html+="</table>"
                return html;
            }

            function initData(){
                axios.get('{{route('admin.item.all')}}')
                .then((res)=>{
                    items=res.data;
                    $('#keyword').search({
                        renderfunc: "renderItem",
                        filterfunc: "filteritems",
                        rendercustom: true,
                        renderele: "#item-list",
                        mod: "item_search",
                        renderfirst:true,
                        list:items
                    })
                    $('#free_item').search({
                        renderfunc: "renderFreeItem",
                        filterfunc: "filteritems",
                        mod: "item_free",
                        list:items
                    })
                });
            }
            initData();
        @endif
    </script>
@endsection
