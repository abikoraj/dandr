@extends('admin.layouts.app')
@section('title', 'Items - Stock Out')
@section('head-title')
    <a href="{{ route('admin.item.index') }}">Items</a> /
    <a href="{{ route('admin.item.stockout-list') }}">Stock Outs</a> / Add
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="">
                <label for="date">Date</label>
                <input type="text" name="date" id="date" class="form-control calender">
            </div>
            <div class="">
                <label for="center_id">Branch</label>
                <select name="center_id" id="center_id" class="form-control ms">
                    @foreach ($centers as $center)
                        <option value="{{ $center->id }}">{{ $center->name }}</option>
                    @endforeach
                </select>
            </div>

            <hr>
            <div class="">
                <label for="barcode">barcode</label>
                <input type="text" onkeydown="searchItem(event,this)" name="barcode" id="barcode" class="form-control">
            </div>

            <div id="test" tabindex="-1" class="" onfocus="searchable=true;listItems(this.value);"   onblur="searchable=false;listItems(this.value);">
                <div style="max-height: 100px;padding:5px;overflow-y:auto;" id="item-search">

                </div>
                <label for="item">Item</label>
                {{-- @if (!env('large', false))
                    <select name="item" id="item" class="form-control ms" onchange="itemChange(this)">
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}">{{ $item->title }}</option>
                        @endforeach
                    </select>
                @else --}}
                <input oninput="listItems(this.value);"
                   onkeydown="searchItemName(event,this)" type="text"
                    name="item" id="item" class="form-control">

                {{-- @endif --}}
            </div>

            <div class="">
                <label for="qty">Qty</label>
                <input class="form-control" type="number" name="qty" id="qty"
                    onkeydown="if(event.which==13){addToList();}">
            </div>
            <div class="pt-2">

                <button class="btn btn-primary w-100" type="text" onclick="addToList()">Add To List </button>
            </div>

        </div>
        <div class="col-md-9">
            <div class=" shadow">
                <div>

                    <table class="table table-bordered">
                        <tr>
                            <th>
                                Item
                            </th>
                            <th>
                                Qty
                            </th>
                        </tr>
                        <tbody id="selectedItems">

                        </tbody>
                    </table>

                </div>
            </div>
            <div class="mt-2 text-right">
                <button class="btn btn-primary" onclick="saveData();">Save Stock Out</button>
            </div>
        </div>
    </div>



@endsection
@section('js')
    <script>
        var selectedItems = [];
        var item;
        var from =1;
        var searchable = false;
        var selectedIndex = -1;
        running=false;
        const items = {!! json_encode($items) !!}

        function searchItem(e, ele) {

            if (e.which == 13) {
                from=1;
                item = items.find(o => o.number == ele.value);
                if (item != undefined) {
                    $('#item').val(item.id);
                    $('#qty').focus();
                } else {
                    showNotification('bg-danger', 'Item Not found');
                }
            }
        }

        function searchItemName(e, ele) {
            if (e.which == 13) {
                e.preventDefault();
                $($('#item-search>div')[selectedIndex])[0].click();
            } else if (e.which == 38) {
                e.preventDefault();
                selectedIndex -= 1;
                if (selectedIndex < 0) {
                    selectedIndex = $('#item-search>div').length - 1;
                }
                changeSelection();
                console.log(selectedIndex);
            } else if (e.which == 40) {
                e.preventDefault();
                selectedIndex += 1;
                if (selectedIndex >= $('#item-search>div').length) {
                    selectedIndex = 0;
                }
                changeSelection();

            }
        }

        function changeSelection() {
            try {
                $('#item-search>div').removeClass('btn-primary');
                $($('#item-search>div')[selectedIndex]).addClass('btn-primary');
                $($('#item-search>div')[selectedIndex])[0].scrollIntoView();

            } catch (error) {

            }

        }

        function selItem(id) {
            from=2;
            item = items.find(o => o.id == id);
                if (item != undefined) {
                    $('#qty').focus();
                    $('#item').val(item.title);
                } else {
                    $('#item').focus();
                }
        }

        function listItems(keyword) {
            let html = '';
            if (searchable) {
                const _items = items.filter(o => o.title.toLowerCase().startsWith(keyword.toLowerCase())).splice(0, 10);
                console.log(_items);
                _items.forEach(_item => {
                    html += "<div onclick='selItem(" + _item.id + ")' data-id='" + _item.id + "'>" + _item.title +
                        "</div>"
                });
            }
            $('#item-search').html(html);
            selectedIndex=0;
            if(html.length>0){
                changeSelection();
            }
        }


        function itemChange(ele) {
            const item_id = $(ele).val();
            // item = items.find(o => o. == ele.item_id);
        }

        function addToList() {
            try {
                if (item == undefined || item == null) {
                    showNotification('bg-danger', 'Please Select a Item');
                    return;
                }
                const qty = parseFloat($('#qty').val());
                if (qty <= 0 ||isNaN(qty)) {
                    showNotification('bg-danger', 'Please Input quantity');
                    return;
                }
                const _localitem = selectedItems.find(o => o.item_id == item.id);
                if (_localitem == undefined) {
                    selectedItems.push({
                        item_id: item.id,
                        qty: qty,
                        title: item.title
                    })
                } else {
                    if (confirm('Item already in list do you want to add to quantity?')) {
                        _localitem.qty += qty;
                    }
                }
                item = null;
                $('#barcode').val('');
                $('#item').val('');
                $('#qty')[0].value = '';
                if(from==1){

                    $('#barcode').focus();
                }else{
                    $('#item').focus();
                }
                renderList();
            } catch (error) {
                console.error(error);
                showNotification('bg-danger', 'Please Enter all Data');
                return;
            }

        }

        function renderList() {
            let html = ""
            $.each(selectedItems, function(indexInArray, selectedItem) {
                html += "<tr><td>" + selectedItem.title + "</td><td>" + selectedItem.qty +
                    "</td><td><button class='btn btn-danger' onclick='remove(" + selectedItem.item_id +
                    ")'>Remove</button></td></tr>"
            });
            $('#selectedItems').html(html);
        }

        function saveData(){
            if(running){
                showNotification('bg-danger', 'Data is Saving');
                return;
            }
            if(selectedItems.length==0){
                showNotification('bg-danger', 'No item in list');
                return;
            }
            if(confirm('Are You Sure All Data Are Correct?')){
                running=true;
                _data={
                    info:{
                        date:$('#date').val(),
                        center_id:$('#center_id').val(),
                    },
                    items:selectedItems
                };
                // _data['info']={
                //     date:$('#date').val(),
                //     center_id:$('#center_id').val(),
                // }
                // _data['items']=selectedItems;

                axios.post('{{route('admin.item.stockout')}}',_data)
                .then((res)=>{
                    selectedItems=[];
                    renderList();
                    const print_url="{{route('admin.item.stockout-print',['id'=>'xxx_id'])}}";
                    console.log(res.data);
                    window.open(print_url.replace('xxx_id',res.data));
                    running=false;
                })
                .catch((err)=>{
                    showNotification('bg-danger', 'Some Error Occured Please Try again');
                    running=false;

                })
            }
        }

        $(document).ready(function () {
            $("#test").focusin(function() {
                searchable=true;
                listItems($('#item').val());
            });

            $("#test").focusout(function() {

                searchable=false;
                listItems('');
            });
        });
    </script>
@endsection
