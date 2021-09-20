@extends('pos.layout.index')
@section('title')
    <span class="text-white">
        {{ env('APP_NAME', 'laravel') }} - {{ $counter->name }}
    </span>
@endsection
@section('content')
    <div class="row m-0 h-100 ">
        <div class="col-md-8">
            <div id="panel1">
                <div id="barcode-container">
                    <input type="text" placeholder="Enter Item Code OR Scan BarCode (f1)" id="barcode">
                    <img src="images/barcode.svg" alt="" srcset="">
                    <img src="images/search.svg" alt="" srcset="">
                </div>

                @include('pos.layout.particular')
                @include('pos.layout.particular_calculation')

            </div>
        </div>
        <div class="col-md-4 ps-0">

            @include('pos.layout.item_selector')
            @include('pos.layout.customer_selector')
            @include('pos.layout.payment')
        </div>
    </div>
    @include('pos.layout.dayclose')
@endsection
@section('prejs')
    <script src="http://localhost:4200/signalr/hubs"></script>
    <script>
        function addToBarcode(barcode) {
            $('#barcode').val(barcode);
            $('#barcode').clearSearch();
        }

        function renderBarcode() {
            html = "<table>";
            console.log(this);
            this.forEach((item) => {

                html +=
                    '<tr class="search-item" id="item-' +
                    dotSanitize(item) +
                    '" onclick="addToBarcode(\'' + item + '\');">' +
                    '<td class="p-1"><span style="cursor: pointer;">' +
                    item +
                    "</span></td>" +
                    "</tr>";

            });
            html += "</table>";
            return html;
        }

        function addToItem(item) {
          console.log(item,'add to item');
          $('#item-name').val(item.name);
          $('#item-rate').val(item.rate);
          $('#item-name').clearSearch();
          billpanel.selectedItem=item;
          $('#item-qty').focus().select();
        }

        function renderItem() {

            html = "<table>";
            console.log(this);
            this.forEach((item) => {

                html +=
                    '<tr class="search-item" '+' " id="item-' +
                    dotSanitize(item.barcode) +
                    '" data-item=\''+JSON.stringify(item)+'\' onclick="addToItem(JSON.parse(this.dataset.item));">' +
                    '<td class="p-1"><span style="cursor: pointer;">' +
                    item.name +
                    "</span></td>" +
                    "</tr>";

            });
            html += "</table>";
            return html;

        }

        function filterItem(_keyword) {
            console.log(this,_keyword);
            billpanel.selectedItem=null;
            let _list=[];
            let _index=0;
            for (let index = 0; index < this.length; index++) {
                const element = this[index];

                if (element.name.toLowerCase().startsWith(_keyword.toLowerCase())) {
                    _list.push(element);
                    if (_index >= 100) {
                        break;
                    }
                    _index += 1;
                }
            }
            return _list;

        }
    </script>
    @include('admin.search.list')
    @include('pos.layout.shortcutjs')
    @include('pos.layout.dayclosejs')
    @include('pos.layout.mainjs')

@endsection
