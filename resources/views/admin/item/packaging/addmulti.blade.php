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
                        <span><input type="checkbox" name="ismanual" id="ismanual"> Manual</span>
                    </label>
                    <input type="number" step="0.0001" class="form-control" id="to_amount" readonly>
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
            <div><button class="btn btn-primary" onclick="save();">Add Repackaging</button></div>
        </div>
    </div>


@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script>
        const items = {!! json_encode($items) !!};
        const units = {!! json_encode($units, JSON_NUMERIC_CHECK) !!};
        var datas = [];
        var ratio = 1;
        var i = 1;
        var toids=[];
        console.log(items, units);
        $(document).ready(function() {
            const items_options = items.map(o => '<option value="' + o.id + '">' + o.title + '</option>').join("");
            $('#from_item_id').html('<option></option>' + items_options);
            $('#to_item_id').html('<option><a/option>' + items_options);
            $('#from_item_id').select2();
            $('#to_item_id').select2();
            $('#ismanual').change(function() {
                if (this.checked) {
                    $('#to_amount').removeAttr('readonly');
                } else {
                    $('#to_amount').attr('readonly', 'true');
                }
            });
            $('#from_amount').keydown(function(e){
                if(e.which==13){
                    if(this.value!=0 && this.value!=''){
                        $('#to_item_id').select2('open');

                    }
                }
            });
            $('#to_amount').keydown(function(e){
                if(e.which==13){
                    const from_amount=$('#from_amount').val();
                    if(this.value!=0 && this.value!='' && from_amount!=0 && from_amount!=''){
                            $('#addbtn').click();
                    }
                }
            });

            $('#from_item_id').change(function() {
                try {
                    console.log(this.value);

                    const item = items.find(o => o.id == this.value);
                    if (item == undefined) {
                        return;
                    }
                    const unit = units.find(o => o.id == item.conversion_id);
                    console.log(item, unit);
                    toids = [unit.id];
                    if (unit.parent_id == 0) {
                        toids = todos.concat(units.filter(o => o.parent_id == unit.id));
                    } else {
                        toids.push(units.find(o => o.id == unit.parent_id).id);
                        toids = toids.concat(units.filter(o => o.parent_id == unit.parent_id).map(o => o.id));
                    }


                } catch (error) {
                    console.log(error);
                }
                $('#ismanual')[0].checked=true;
                $('#to_amount').removeAttr('readonly');
                $('#from_amount').focus();
            });


            $('#from_amount').change(function() {

                if (!($('#ismanual')[0].checked)) {
                    $('#to_amount').val(ratio * $('#from_amount').val());
                }
            });

            $('#to_item_id').change(function() {
                try {

                    const to = items.find(o => o.id == $('#to_item_id').val());
                    const from = items.find(o => o.id == $('#from_item_id').val());
                    if(toids.includes(to.conversion_id)){
                        if (to != undefined && from != undefined) {
                            const tounit = units.find(o => o.id == to.conversion_id);
                            const fromunit = units.find(o => o.id == from.conversion_id);
                            if (tounit.parent_id == 0) {
                                ratio = fromunit.main / fromunit.local;
                            } else {
                                const fromratio = fromunit.main / fromunit.local;
                                const toratio = tounit.main / tounit.local;
                                ratio = fromratio / toratio;
                            }
                            console.log(tounit, fromunit, ratio);

                            $('#ismanual')[0].checked=false;
                            $('#to_amount').arr('readonly','true');
                            $('#to_amount').val(ratio * $('#from_amount').val());
                        }
                    }else{
                        $('#ismanual')[0].checked=true;
                        $('#to_amount').removeAttr('readonly');
                    }
                } catch (error) {

                }
                $('#to_amount').focus();
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
                data.splice(index, 1);
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
            axios.post('{{ route('admin.item.packaging.add') }}', {
                datas:extractedDatas,
                center_id:$('#center_id').val(),
                date:$('#date').val(),
            })
                .then((res) => {
                    console.log(res);
                    datas = [];
                    renderHtml();
                    showNotification('bg-success','Successfully repackaged');
                })
                .catch((err) => {
                    console.log(err);
                    showNotification('bg-danger','Error while repackaging');

                });
        }
    </script>
@endsection
