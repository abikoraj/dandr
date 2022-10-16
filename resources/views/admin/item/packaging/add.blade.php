@extends('admin.layouts.app')
@section('title', 'Items - Packing')
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
@endsection
@section('head-title')
    <a href="{{ route('admin.item.index') }}">Items</a> /
    <a href="{{ route('admin.item.packaging.index') }}">Packaging</a> /
    Add
@endsection

@section('toobar')

@endsection
@section('content')
    <div class="shadow mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4  ">
                    <label>
                        From Product
                    </label>
                    <select name="from_item_id" id="from_item_id" class="select2 ms show-tick form-control">

                    </select>
                </div>
                <div class="col-md-2 ">
                    <label for="">QTY</label>
                    <input type="number" step="0.0001" class="form-control" id="from_amount">
                </div>

                <div class="col-md-4 ">
                    <label>
                        To Product
                    </label>
                    <select name="to_item_id" id="to_item_id" class="select2 ms show-tick form-control">

                    </select>
                </div>
                <div class="col-md-2 ">
                    <label for="" class="d-flex justify-content-between">
                        <span>Qty</span>
                    </label>
                    <input type="number" step="0.0001" class="form-control" id="to_amount" >
                </div>
                <div class="col-12 pt-2">
                    <button class="btn btn-primary focusable" id="addbtn">Add </button>
                </div>

            </div>
        </div>
    </div>
    <div class="shadow">
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <label for="data">Date</label>
                    <input type="text" name="date" id="date" class="form-control calender">
                </div>
                @if (env('multi_stock'))
                    <div class="col-md-6">
                        <label>Center / Branch</label>
                        <select name="center_id" id="center_id" class="form-control ms">
                            @foreach ($centers as $center)
                                <option value="{{ $center->id }}">{{ $center->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

            </div>
            <hr>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>From Item</th>
                        <th>From Amount</th>
                        <th>To Item</th>
                        <th>To Amount</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="alldata">

                </tbody>
            </table>
            <hr>
            <div class="row">
                @include('admin.item.packaging.material')
                @include('admin.item.packaging.cost')

            </div>
            <hr>
            <div>
                <button class="btn btn-primary" onclick="save();">Add Repackaging</button>
                <button class="btn btn-danger" onclick="reset(1);">Clear</button>
            </div>
        </div>
    </div>


@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script>
        const items = {!! json_encode($items) !!};
        var datas = [];
        var costs = [];
        var materials = [];
        var ratio = 1;
        var i = 1;
        var toids = [];
        $(document).ready(function() {
            const items_options = items.map(o => '<option value="' + o.id + '">' + o.title + '</option>').join("");
            $('#from_item_id').html('<option></option>' + items_options);
            $('#to_item_id').html('<option><a/option>' + items_options);
            $('#material_id').html('<option><a/option>' + items_options);
            $('#from_item_id').select2();
            $('#to_item_id').select2();
            $('#material_id').select2();
           
            $('#from_amount').keydown(function(e) {
                if (e.which == 13) {
                    if (this.value != 0 && this.value != '') {
                        $('#to_item_id').select2('open');

                    }
                }
            });
            $('#to_amount').keydown(function(e) {
                if (e.which == 13) {
                    const from_amount = $('#from_amount').val();
                    if (this.value != 0 && this.value != '' && from_amount != 0 && from_amount != '') {
                        $('#addbtn').click();
                    }
                }
            });

          
    

            $('#addbtn').click(function() {

                const to = items.find(o => o.id == $('#to_item_id').val());
                const from = items.find(o => o.id == $('#from_item_id').val());
                if (to == undefined || from == undefined) {
                    alert("Please select items");
                    return;
                }
                const toamount = $('#to_amount').val();
                const fromamount = $('#from_amount').val();
                if (toamount == '0' || toamount == '' || fromamount == '0' || fromamount == '') {
                    alert("Please Enter  Quantity");
                    return;
                }
                datas.push({
                    identifire: i++,
                    from_item_id: from.id,
                    from_amount: fromamount,
                    to_item_id: to.id,
                    to_amount: toamount,
                    from_title: from.title,
                    to_title: to.title,
                });

                $('#to_amount').val('0');
                $('#from_amount').val('0');
                $('#from_item_id').val(null).trigger('change');
                $('#to_item_id').val(null).trigger('change');
                $('#from_item_id').focus();
                $('#from_item_id').select2('open');


                renderHtml();

            });
        });


        function remove(id) {
            const index = datas.findIndex(o => o.identifire == id);
            if (index != -1) {
                datas.splice(index, 1);
            }
            renderHtml();
        }

        function renderHtml() {
            let html = "";
            datas.forEach(data => {
                html += `<tr><td>${data.from_title}</td><td>${data.from_amount}</td><td>${data.to_title}</td><td>${data.to_amount}</td>
                <td><button onclick="remove(${data.identifire})" class="btn btn-danger">remove</button></td>
                </tr>`;
            });
            $('#alldata').html(html);
            console.log(html);
        }

        function save() {
            if (datas.length == 0) {
                alert('Please Add at least one entry to save.');
                return;
            }
            if (prompt('Enter yes to continue') != 'yes') {
                return;
            }
            const extractedDatas = datas.map((o) => {
                return {
                    from_item_id: o.from_item_id,
                    from_amount: o.from_amount,
                    to_item_id: o.to_item_id,
                    to_amount: o.to_amount,
                }
            });
            const extractedCosts = costs.map((o) => {
                return {
                    title: o.title,
                    amount: o.amount,
                }
            })
            const extractedMaterials = materials.map((o) => {
                return {
                    item_id: o.item_id,
                    qty: o.qty,
                }
            })

            axios.post('{{ route('admin.item.packaging.add') }}', {
                    datas: extractedDatas,
                    costs: extractedCosts,
                    materials: extractedMaterials,
                    center_id: $('#center_id').val(),
                    date: $('#date').val(),
                })
                .then((res) => {
                    console.log(res);
                    reset(0);
                    showNotification('bg-success', 'Successfully repackaged');
                })
                .catch((err) => {
                    console.log(err);
                    showNotification('bg-danger', 'Error while repackaging');

                });
        }

        function reset(type) {
            if(type==1){
                if(!(confirm('Do want to reset the process?'))){
                    return;
                }
            }
            datas = [];
            costs = [];
            materials = [];
            renderHtml();
            renderMaterialHtml();
            renderCostHtml();
        }
    </script>
@endsection
