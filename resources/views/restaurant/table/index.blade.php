@extends('restaurant.layout.app')
@section('content')
    @include('restaurant.table.style')
    <div class="p-5">

        <div class="d-flex">
            @foreach ($sections as $section)
                <button class="btn btn-selector px-5 btn-{{ $section->id }} pr-3"
                    onclick="setSelection({{ $section->id }});">{{ $section->name }}</button>
            @endforeach
        </div>

        <div>
            @foreach ($sections as $section)
                <div class="table-wrapper d-none   p-1 b-1 mt-2 " id="table-wrapper-{{ $section->id }}"
                    onclick="setSelection({{ $section->id }});">
                    <div class="row">
                        @foreach ($tables->where('section_id', $section->id) as $table)
                            <div class="col-md-4 " ondblclick="showOrderPad({{$table->id}},'{{$table->name}}')">
                                <div class="shadow tables p-2"  id="table={{$table->id}}" >
                                    <h5 class="px-2">{{ $table->name }}</h5>
                                    <div>
                                        <table class="table">
                                            <tr>
                                                <th>Item</th>
                                                <th>Qty</th>
                                                <th></th>
                                            </tr>
                                            <tbody id="order-{{ $table->id }}">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <div class="sideHolder">
        <div class="sticky-top bg-white p-2 shadow">
            <div class="row">
                <div class="col-12">
                    <input type="hidden" id="table_id" name="table_id">
                    <h5 >
                       Table :  <span id="table_name"></span>
                    </h5>
                    <hr>
                </div>
                <div class="col-6">
                    <div id="test" tabindex="0"  onfocus="changeFocus(true);" onblur="changeFocus(false);">
                        <div class="searchItemHolder" id="item-search">

                        </div>
                        <label for="item">Item</label>
                        <input oninput="listItems(this.value);" 
                            onfocus="changeFocus(true);"
                            onkeydown="searchItemName(event,this)" 
                            onblur="changeFocus(false);"
                            type="text" 
                            name="item_id" 
                            id="item_id"
                            class="form-control">
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
                    <th>Item</th>
                    <th>Qty</th>
                </tr>
                <tbody id="sideHolderData">

                </tbody>
            </table>
        </div>

    </div>
@endsection
@section('scripts')
    <script>
        //local data
        const tables = {!! json_encode($tables) !!};
        const sections = {!! json_encode($sections) !!};
        const items = {!! json_encode($items) !!};
        let currentData=[];
        //for search
        var selectedItems = [];
        var item;
        var from = 1;
        var searchable = false;
        var selectedIndex = -1;
        running = false;

        id=1;
        var current = {{ $sections[0]->id }};
        const tableData = [];
        $(document).ready(function() {

            const currentDataStr= localStorage.getItem('currentData');
            if(currentDataStr!=null && currentDataStr!=undefined){
                currentData=JSON.parse(currentDataStr);
            }
            const idStr= localStorage.getItem('id');
            if(idStr!=null || idStr!=undefined){
                id=parseInt(idStr);
            }

            sections.forEach(section => {
                tableData.push({
                    section: section,
                    tables: tables.filter(o => o.section_id == section.id)
                });
            });

            

            $('#qty').keydown(function (e) { 
                if(e.which==13){
                    addItem();
                }
            });
            setSelection(current);
          
        });



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
           
            const table_id=$('#table_id').val();
            const qty=$('#qty').val();
            const index=currentData.findIndex(o=>o.table_id==table_id);
            if(index<0){
                currentData.push({
                    table_id:table_id,
                    items:[
                        {
                            id:id,
                            item:item,
                            qty:qty
                        }
                    ]
                });
            }else{
                currentData[index].items.push( {
                            id:id,
                            item:item,
                            qty:qty
                        });
            }
            save();
            renderSide();
        }

        function save(){
            id+=1;
            localStorage.setItem('currentData',JSON.stringify(currentData));
            localStorage.setItem('id',JSON.stringify(id));
        }

        var currentTable;
        function showOrderPad(id,name) {
            $('.sideHolder').css('display', 'block');
            $('#table_name').html(name);
            $('#table_id').val(id);
            currentTable={name:name,id:id};
            renderSide();

        }

        function renderSide(){
            $('#sideHolderData').html('');
            $('')
            const index=currentData.findIndex(o=>o.table_id==currentTable.id);
            if(index>-1){
                localData=currentData[index];
                $('#sideHolderData').html(
                    localData.items.map(o=>{
                        return `<tr><td>${o.item.title}</td><td>${o.qty}</td></tr>`;
                    }).join('')
                );
            }
        }
    </script>

    @include('restaurant.table.itemsearch')
@endsection
