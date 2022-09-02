@extends('restaurant.layout.app')
@section('content')
    @include('restaurant.table.style')
    <div class="p-2 p-md-5">

        <div class="d-flex">
            @foreach ($sections as $section)
                <button class="btn btn-selector px-5 btn-{{ $section->id }} pr-3"
                    onclick="setSelection({{ $section->id }});">{{ $section->name }}</button>
            @endforeach
        </div>

        <div>
            @foreach ($sections as $section)
                <div class="table-wrapper d-none   p-1  mt-2 " id="table-wrapper-{{ $section->id }}"
                    onclick="setSelection({{ $section->id }});">
                    <div class="row">
                        @foreach ($tables->where('section_id', $section->id) as $table)
                            <div class="col-md-6 col-lg-4 mb-1 "
                                ondblclick="showOrderPad({{ $table->id }},'{{ $table->name }}')">
                                <div class="shadow tables p-2" id="table={{ $table->id }}">
                                    <h5 class="px-2">{{ $table->name }}</h5>
                                    <div class="orders">
                                        <div id="order-{{ $table->id }}" class="row ">

                                        </div>
                                        {{-- <table class="table">
                                            <tr>
                                                <th>Item</th>
                                                <th>Qty</th>
                                                <th></th>
                                            </tr>
                                        </table> --}}
                                    </div>
                                    <div>
                                        <button class="btn btn-primary" onclick="openBill({{ $table->id }})">Send to
                                            bill</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <div class="sideBar-{{ csrf_token() }}">
        <div class="sideHolder-{{ csrf_token() }}">
            <div class="sticky-top bg-white p-2 shadow">
                <div class="row">
                    <div class="col-12">
                        <input type="hidden" id="table_id" name="table_id">
                        <div class="d-flex justify-content-between">
                            <h5>
                                Table : <span id="table_name"></span>

                            </h5>
                            <span>

                                <button class="btn btn-success" onclick="printKOT(1)" >Print KOT</button>
                                <button class="btn btn-success" onclick="printKOT(2)" >Print KOT Selected</button>
                            </span>

                        </div>
                        <hr>
                    </div>
                    <div class="col-6">
                        <div id="test" tabindex="0" onfocus="changeFocus(true);" onblur="changeFocus(false);">
                            <div class="searchItemHolder" id="item-search">

                            </div>
                            <label for="item">Item</label>
                            <input oninput="listItems(this.value);" onfocus="changeFocus(true);"
                                onkeydown="searchItemName(event,this)" onblur="changeFocus(false);" type="text"
                                name="item_id" id="item_id" class="form-control">
                        </div>
                        {{-- <label for="item_id">Item</label>
                        <select id="item_id" name="item_id" class="form-control show-tick ms select2">
                        </select> --}}
                    </div>
                    <div class="col-6">
                        <label for="qty">Qty</label>
                        <input type="number" name="qty" id="qty" class="form-control">
                    </div>
                </div>
            </div>
            <div class="p-2">

                <table class="table table-bordered">
                    <tr>
                        <th></th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th></th>
                    </tr>
                    <tbody id="sideHolderData">

                    </tbody>
                </table>
            </div>

        </div>

    </div>
@endsection
@section('scripts')
    <script>
        //local data
        const tables = {!! json_encode($tables) !!};
        const sections = {!! json_encode($sections) !!};
        const items = {!! json_encode($items) !!};
        let currentAdded = [];
        let currentData = [];
        //for search
        var selectedItems = [];
        var item;
        var from = 1;
        var searchable = false;
        var selectedIndex = -1;
        running = false;

        id = 1;
        var current = {{ $sections[0]->id }};
        const tableData = [];
        $(document).ready(function() {
            $('.sideHolder-{{ csrf_token() }}').click(function(e) {
                e.stopPropagation();

            });
            $('.sideBar-{{ csrf_token() }}').click(function(e) {
                if(currentAdded.length>0){
                    printKOT(1);
                }
                $('.sideBar-{{ csrf_token() }}').css('display', 'none');
                currentTable = null;
                $('#sideHolderData').html('');


            });
            const currentDataStr = localStorage.getItem('currentData');
            if (currentDataStr != null && currentDataStr != undefined) {
                currentData = JSON.parse(currentDataStr);
            }
            const idStr = localStorage.getItem('id');
            if (idStr != null || idStr != undefined) {
                id = parseInt(idStr);
            }

            sections.forEach(section => {
                tableData.push({
                    section: section,
                    tables: tables.filter(o => o.section_id == section.id)
                });
            });

            currentData.forEach(localData => {
                renderOrder(localData);
            });

            $('#qty').keydown(function(e) {
                if (e.which == 13) {
                    addItem();
                }
            });
            setSelection(current);

            $('.form-control, body').bind('keydown', 'esc', function(e){
                e.preventDefault();
                console.log('esc');
                if(currentAdded.length>0){
                    printKOT(1);
                }
                $('.sideBar-{{ csrf_token() }}').css('display', 'none');
                currentTable = null;
                $('#sideHolderData').html('');
            });

        });


        function renderOrder(localData) {
            $('#order-' + localData.table_id).html(
                localData.items.map(o => {
                    return `<div  class="col-6">(${o.item.title} X ${o.qty} )</div>`;
                }).join('')
            );
        }



        function setSelection(id) {
            current = id;
            $('.btn-selector').removeClass('btn-primary');
            $('.table-wrapper').addClass('d-none');
            $('.btn-' + id).addClass('btn-primary');
            $('#table-wrapper-' + id).removeClass('d-none');

        }

        $('#item_id').change(function() {
            console.log(this.value);
        });

        function addItem() {
            if (item == null || item == undefined) {
                alert("Please Select A Item");
                $('#item_id').focus();

                return;
            }
            const table_id = $('#table_id').val();
            const qty = parseFloat($('#qty').val());
            if (isNaN(qty)) {
                alert("Please Enter Qty");
                $('#qty').focus();
                return;
            }
            const index = currentData.findIndex(o => o.table_id == table_id);

            currentAdded.push({
                id: id,
                item: item.name,
                qty: qty
            });

            if (index < 0) {
                currentData.push({
                    table_id: table_id,
                    items: [{
                        id: id,
                        item: item,
                        qty: qty
                    }]
                });
            } else {
                currentData[index].items.push({
                    id: id,
                    item: item,
                    qty: qty
                });
            }
            item = null;
            $('#qty').val(null);
            $('#item_id').val('');
            $('#item_id').focus();
            save();
            renderSide();
        }

        function save() {
            id += 1;
            localStorage.setItem('currentData', JSON.stringify(currentData));
            localStorage.setItem('id', JSON.stringify(id));
        }

        var currentTable;

        function showOrderPad(id, name) {
            $('.sideBar-{{ csrf_token() }}').css('display', 'block');
            $('#table_name').html(name);
            $('#table_id').val(id);
            $('#item_id').focus();
            currentTable = {
                name: name,
                id: id
            };
            currentAdded = [];
            renderSide();

        }

        function renderSide(type=1) {
            $('#sideHolderData').html('');
            const index = currentData.findIndex(o => o.table_id == currentTable.id);
            if (index > -1) {
                localData = currentData[index];
                $('#sideHolderData').html(

                    localData.items.map(o => {
                        return `<tr>
                            <td><input type="checkbox" class="hasKOT" value="${o.id}"/></td>
                            <td>${o.item.title}</td>
                            <td>${o.qty}</td>
                            <td>
                                <button class="btn btn-danger" onclick="initDel(${o.id},${currentTable.id})"> Del </button>
                            </td>
                        </tr>`;
                    }).join('')
                );
                renderOrder(localData);
                if(type!=2){
                    axios.post('{{ route('restaurant.table') }}', {
                        id: localData.table_id,
                        data: JSON.stringify(localData.items)
                    });
                }
            }
        }


        function openBill(table_id) {
            window.open("{{ route('admin.billing.home') }}?table=" + table_id);

        }

        function clearOrder(id, bill_id) {
            const index = currentData.findIndex(o => o.table_id == id);
            if (index > -1) {
                currentData.splice(index, 1);
                save();
                $('#order-' + id).html('');
            }


        }
        console.log()
    </script>

    @include('restaurant.table.itemsearch')
    @include('restaurant.table.del')
    @include('restaurant.table.kot')
@endsection
