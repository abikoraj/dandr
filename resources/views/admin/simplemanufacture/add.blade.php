@extends('admin.layouts.app')
@section('title', 'Manufacture Items - Add')
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}">
    <style>
        .step-btn {
            cursor: pointer;
            padding: 10px 15px;
            flex: 1;
            text-align: center;
        }



        .step-btn.active {
            background: rgb(0, 122, 204);
            color: white;
        }

        .step-div {
            display: none;

        }

        .step-div.active {
            display: block;
        }
    </style>
@endsection
@section('head-title')
    <a href="{{ route('admin.simple.manufacture.index') }}">Manufacture Items</a> / Add
@endsection
@section('toobar')
@endsection
@section('content')

    <div class="d-flex shadow-local">
        <div class="steps step-btn  step-1 active" onclick="CurrentStep=1;refresh();">
            Raw Materials
        </div>
        <div class="steps step-btn  step-2" onclick="CurrentStep=2;refresh();">
            Produced Items
        </div>
        <div class="steps step-btn  step-3" onclick="CurrentStep=3;refresh();">
            Wastage Materials
        </div>
    </div>
    <div class="p-2 mt-3  shadow-local">
        <div>
            <strong>Date:</strong>
            <input type="text" name="nepali-date" id="nepali-date" class="calender">
        </div>
        <hr>
        <div class="row mt-3">
            <div class="col-12">
                <div class="w-25">

                </div>
            </div>
            <div class="col-md-4">
                <label for="item_id">Item</label>
                <select name="item_id" id="item_id" class="ms form-control">

                </select>
            </div>
            <div class="col-md-3">
                <label for="center_id">Centers</label>
                <select name="center_id" id="center_id" class="ms form-control">

                </select>
            </div>
            <div class="col-md-2">
                <label for="amount">Amount</label>
                <input name="amount" id="amount" class="form-control" step="0.0001">
            </div>
            <div class="col-md-3  d-flex align-items-end">
                <button class="btn btn-primary w-100" onclick="AddData()">
                    Add <br>
                    <span id="add-type">

                    </span>
                </button>
            </div>
        </div>
        <hr>
        <div id="current-stock-holder" > 

            <div class="row">
    
                <div class="col-md-4" >
                    <strong>
                        Current Stock : 
                    </strong>
                    <span id="current-stock"></span> 
                </div>
                <div class="col-md-4">
                    <strong>
                        Loaded Stock : 
                    </strong>
                    <span id="loaded-stock"></span>
                </div>
            </div>
            <hr>
        </div>
        <div>
            <div class="steps step-div step-1 active">
                <table class="table">
                    <tr>
                        <th>
                            Item
                        </th>
                        <th>
                            Center
                        </th>
                        <th>
                            Qty
                        </th>
                        <th></th>

                    </tr>
                    <tbody id="rawMaterialsData">

                    </tbody>
                </table>
            </div>
            <div class="steps step-div step-2">
                <table class="table">
                    <tr>
                        <th>
                            Item
                        </th>
                        <th>
                            Center
                        </th>
                        <th>
                            Qty
                        </th>
                        <th></th>

                    </tr>
                    <tbody id="itemsData">

                    </tbody>
                </table>
            </div>
            <div class="steps step-div step-3">
                <table class="table">
                    <tr>
                        <th>
                            Item
                        </th>
                        <th>
                            Center
                        </th>
                        <th>
                            Qty
                        </th>
                        <th></th>
                    </tr>
                    <tbody id="wastageData">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="p-2 mt-3 shadow-local text-right">
        <button class="btn btn-success" onclick="saveData();">
            Save Manufacture Process
        </button>
    </div>


@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script>
        var centerStocks = {!! json_encode($centerStocks) !!};
        console.log(centerStocks);
        var data = {
            rawMaterials: [],
            wastage: [],
            items: [],
            date: '',
            types: ['rawMaterials', 'items', 'wastage'],
            code: 'simplemanufacture',
            push: function(localdata) {
                switch (CurrentStep) {
                    case 1:
                        data.rawMaterials.push(localdata);
                        break;
                    case 2:
                        data.items.push(localdata);
                        break;
                    case 3:
                        data.wastage.push(localdata);
                        break;

                    default:
                        break;
                }
                data.render();
                data.save();
            },
            clean: function() {
                data.rawMaterials = [];
                data.items = [];
                data.wastage = [];
                data.save();
                data.render();
            },
            clear: function(id, type) {
                const toremove = data.types[type - 1];
                const index = data[toremove].findIndex(o => o.uid == id);
                if (index > -1) {
                    data[toremove].splice(index, 1);
                    data.render();
                    data.save();
                }
            },
            save: function() {
                localStorage.setItem('simplemanufacture', JSON.stringify(data));
            },
            load() {
                const str = localStorage.getItem('simplemanufacture');
                if (str != null) {
                    const localData = JSON.parse(str);
                    data.items = localData.items;
                    data.rawMaterials = localData.rawMaterials;
                    data.wastage = localData.wastage;
                    let max = 0;
                    data.types.forEach(type => {
                        const localDatas = data[type];
                        localDatas.forEach(local => {
                            if (local.uid > max) {
                                max = local.uid;
                            }
                        });
                    });
                    uid += max;
                    data.render();
                }

            },
            render: function() {
                data.types.forEach(type => {
                    const localDatas = data[type];
                    let html = '';

                    localDatas.forEach(localData => {
                        html += `<tr>
                                <td>${localData.item.title}</td>
                                <td>${localData.center.name}</td>
                                <td>${localData.amount}</td>
                                <td><button onclick="data.clear(${localData.uid},${localData.type})">Del</button></td>
                            </tr>`;
                    });
                    $('#' + type + "Data").html(html);
                });
            }
        }
        var CurrentStep = 1;
        var uid = 1;
        const items = {!! json_encode($items) !!};
        const centers = {!! json_encode($centers) !!};
        const maincenter = {{ env('maincenter', -1) }};
        console.log(items, centers);
        steps = ['', 'Raw Materials', 'Produced Items', 'Wastage Materials'];

        function refresh() {
            $('.steps').removeClass('active');
            $('.step-' + CurrentStep).addClass('active');
            $('#add-type').html(steps[CurrentStep]);
            if(CurrentStep==1){
                $('#current-stock-holder').show();
            }else{
                $('#current-stock-holder').hide();

            }
        }

        $(document).ready(function() {
            refresh();
            $('#item_id').html("<option></option>" + (items.map(i => `<option value="${i.id}">${i.title}</option>`)
                .join('')));
            $('#item_id').select2();

            $('#center_id').html((centers.map(c => {
                if (c.id == maincenter) {
                    return `<option value="${c.id}" selected>${c.name}</option>`;

                } else {

                    return `<option value="${c.id}">${c.name}</option>`;
                }
            }).join('')));
            data.load();

            $('#item_id').change((e) => {
                $('#current-stock').html("");

                if (CurrentStep == 1) {
                    const item_id = parseInt($('#item_id').val());
                    const center_id = parseInt($('#center_id').val());
                    const stock = centerStocks.find(o => o.item_id == item_id && o.center_id == center_id);
                    console.log(stock);
                    if (stock != undefined) {
                        $('#current-stock').html(stock.amount<0?0:stock.amount);
                    }
                    let loadedStockAmount =0;
                    const loadedStocks=data.rawMaterials.filter(o=>o.item.id==item_id && o.center.id==center_id);
                    loadedStocks.forEach(loadedStock => {
                        loadedStockAmount+=loadedStock.amount;
                    });
                    $('#loaded-stock').html(loadedStockAmount);

                }
            })
        });

        function saveData() {
            if (data.items.length == 0) {
                alert('Please Enter Produced Item');
                return;
            }
            if (data.rawMaterials.length == 0) {
                alert('Please Enter Raw Materials');
                return;
            }

            if (!prompt('Enter yes to continue') == 'yes') {
                return;
            }
            data.date = $('#nepali-date').val();
            const localData = {
                date: data.date,
                items: [],
                item_ids: []
            };

            data.types.forEach(type => {
                const localDatas = data[type];
                localDatas.forEach(local => {
                    localData.items.push({
                        item_id: local.item.id,
                        item_title: local.item.title,
                        center_id: local.center.id,
                        amount: local.amount,
                        type: local.type,
                    })
                    localData.item_ids.push(local.item.id);
                });
            });
            showProgress('Saving Manufacture');

            axios.post("{{ route('admin.simple.manufacture.add') }}", localData)
                .then((res) => {
                    data.clean();
                    hideProgress();
                    showNotification("bg-success", "Manufacture Added Successfully");
                })
                .catch((err) => {
                    hideProgress();
                    if (err.response) {
                        showNotification("bg-danger", err.response.data.message)
                    } else {
                        showNotification("bg-danger", "Some Error Occured");
                    }
                })
        }

        function AddData() {
            const amount = parseFloat($('#amount').val());
            const item_id = parseInt($('#item_id').val());
            const center_id = parseInt($('#center_id').val());

            let canAdd = true;
            if (isNaN(item_id)) {
                alert('Please Enter Amount');
                canAdd = false;
                $('#item_id').select2('open');
                $('#item_id').select2('focus');
            }
            if (isNaN(amount)) {
                alert('Please Enter Amount');
                canAdd = false;

            }

            if (CurrentStep == 1) {

                const stock = centerStocks.find(o => o.item_id == item_id && o.center_id == center_id);
                if (stock != undefined) {
                    const stockAmount = parseFloat(stock.amount);
                    let loadedStockAmount =0;
                    const loadedStocks=data.rawMaterials.filter(o=>o.item.id==item_id && o.center.id==center_id);
                    console.log(stock, amount, CurrentStep,loadedStocks,loadedStockAmount, "all data");
                    loadedStocks.forEach(loadedStock => {
                        loadedStockAmount+=loadedStock.amount;
                    });
                    
                    if (amount > (stockAmount-loadedStockAmount)) {
                        alert('Not Enough Stock');
                        return;
                    }
                } else {
                    alert('Not Enough Stock');
                    return;
                }
            }
            if (canAdd) {
                const localdata = {
                    item: items.find(o => o.id == item_id),
                    center: centers.find(o => o.id == center_id),
                    amount: amount,
                    uid: uid++,
                    type: CurrentStep
                };

                console.log(localdata);
                $('#amount').val(null).change();
                $('#item_id').val(null).change();
                // $('#center_id').val(null).change();
                // $('#item_id').select2();
                $('#item_id').select2('focus');
                $('#item_id').trigger({
                    type: 'select2:open'
                });
                data.push(localdata);
                $('#current-stock').html('');
            }

        }

        $('#amount').keydown(function(e) {
            if (e.which == 13) {
                AddData();
            }
        });
    </script>
@endsection
