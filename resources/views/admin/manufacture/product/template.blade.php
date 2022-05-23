@extends('admin.layouts.app')
@section('title')
    Manufacture Items / {{ $product->title }} / Template
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
@endsection
@section('head-title')
    <a href="{{ route('admin.manufacture.product.index') }}">Manufacture Items</a>
    / {{ $product->title }} / Template
@endsection
@section('toobar')
@endsection
@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
    <div class="shadow p-2 mb-3">

        <form action="{{ route('admin.manufacture.product.template.add', ['id' => $product->id]) }}" method="POST"
            id="addManufacturedProductItem">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <label for="item_id">Item</label>
                    <select name="item_id" id="item_id" class="form-control ms" required>

                    </select>
                </div>
                <div class="col-md-4">
                    <label for="amount">Amount per {{ $product->unit }} of {{ $product->title }}</label>
                    <input type="number" step="0.001" class="form-control" name="amount" id="amount" required>

                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div>
                        <button class="btn btn-primary w-100">Save</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
    <div class="shadow p-2">
        <div class="row">
            <div class="col-md-4">
                <strong>Item</strong>
            </div>
            <div class="col-md-4">
                <strong>

                    Amount per {{ $product->unit }} of {{ $product->title }}
                </strong>
            </div>
        </div>
        <hr>
        <div id="manufaturedProductItems">

        </div>
    </div>

    <span class="d-none" id="template">

        <div id="item_xxx_id">
            <form  action="{{ route('admin.manufacture.product.template.update', ['id' => 'xxx_id']) }}"
                onsubmit="return update(this,event);">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <strong>xxx_title</strong>
                    </div>
                    <div class="col-md-4">
                        <input step="0.001" type="number" value="xxx_amount" name="amount" id="amount_xxx_id"
                            class="form-control">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-success">Save</button>
                        <span onclick="del(xxx_id)" class="btn btn-danger">Delete</span>
                    </div>
                </div>
            </form>
        </div>
    </span>
@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script>
        var lockk = false;
        const items = {!! json_encode($items) !!};
        const manufaturedProductItems = {!! json_encode($manufaturedProductItems) !!};
        var template = "";
        console.log(items);

        $(document).ready(function() {
            // let itemOptions='<option ></option>';
            // items.forEach(item => {
            //     itemOptions+="<option value='"+item.id+"'>"+item.title +" ("+item.unit+")</option>"
            // });
            template = $('#template').html();
            $('#item_id').html('<option ></option>' + items.map(item => "<option value='" + item.id + "'>" + item
                .title + " (" + item.unit + ")</option>"));

            $('#item_id').select2({
                placeholder: 'Select a Item'
            });

            $('#manufaturedProductItems').html(manufaturedProductItems.map(o => renderItem(o)));



            $('#addManufacturedProductItem').submit(function(e) {
                e.preventDefault();
                console.log(this.action);
                showProgress("Adding Manufatured Product Template");
                axios.post(this.action, new FormData(this))
                    .then((res) => {
                        const data = res.data;
                        data.title = $("#item_id option:selected").text();
                        $('#manufaturedProductItems').append(renderItem(data));
                        this.reset();
                        $('#item_id').val(null).trigger('change');
                        hideProgress();
                        showNotification('bg-success', 'Template item added succesfully;');
                    })
                    .catch((err) => {
                        showNotification('bg-danger', 'Template item cannot be added;');
                        hideProgress();
                    });
            });
        });

        function del(id) {
            if(prompt('Enter yes to delete template item')=='yes'){
                showProgress('Deleting Template item');
                axios.post('{{route('admin.manufacture.product.template.del')}}', {id:id})
                    .then((res) => {
                        $('#item_'+id).remove();
                        hideProgress();
                        showNotification('bg-success', 'Template item deleted succesfully;');
                    })
                    .catch((err) => {
                        showNotification('bg-danger', 'Template item cannot be deleted;');
                        hideProgress();
                    });
            }
        }
        function update(ele, e) {
            e.preventDefault();
            showProgress("Updating manufatured product template item");
            axios.post(ele.action, new FormData(ele))
                .then((res) => {

                    hideProgress();
                    showNotification('bg-success', 'Template item updated succesfully;');
                })
                .catch((err) => {
                    showNotification('bg-danger', 'Template item cannot be updated;');
                    hideProgress();
                });

        }

        function renderItem(o) {
            let html = template.replaceAll('xxx_id', o.id);
            html = html.replaceAll('xxx_title', o.title);
            html = html.replaceAll('xxx_amount', o.amount);
            return html;
        }
    </script>
@endsection
