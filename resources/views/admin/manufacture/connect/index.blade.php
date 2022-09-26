@extends('admin.layouts.app')
@section('title', 'Cheese Item Management')
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
@endsection
@section('head-title', 'Cheese Item Management')
@section('toobar')
@endsection
@section('content')
    <form action="{{ route('admin.manufacture.cheese.index') }}" method="post" id="addItem">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <label for="item_id">Final Sales Product</label>
                <select name="item_id" id="item_id" class="form-control ms">

                </select>
            </div>
            <div class="col-md-4">
                <label for="target_item_id">Manufactured Item</label>
                <select name="target_item_id" id="target_item_id" class="form-control ms">

                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary w-100">Add</button>
            </div>
        </div>
    </form>
    <br>
    <table class="table table-bordered">
        <thead>

            <tr>
                <th>Final Sales Product</th>
                <th>Manufactured Item</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="data">

        </tbody>
    </table>


@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script>
        const connectedItems = {!! json_encode($connectedItems) !!};
        const items = {!! json_encode($items) !!};
        const manufacturedItems = {!! json_encode($manufacturedItems) !!};

        function render(o) {
            const item = items.find(i => i.id == o.item_id);
            const manufacturedItem = manufacturedItems.find(i => i.id == o.target_item_id);
            return `
                <tr id="item-${o.id}">
                    <td>${item.title}</td>
                    <td>${manufacturedItem.title}</td>
                    <td>
                        <button class="btn btn-danger" onclick="del(${o.id})">Del</button>
                    </td>
                </tr>
            `;
        }

        function del(id) {
            if (prompt('Enter yes to continue') == 'yes') {
                showProgress("Deleting Item");
                axios.post('{{ route('admin.manufacture.cheese.del') }}', {
                        id: id
                    })
                    .then((res) => {
                        $('#item-' + id).remove();
                        hideProgress();
                        showNotification('bg-success', 'Item deleted sucessfully');

                    }).catch((err) => {
                        hideProgress();
                        const msg = err.response ? err.response.data.message : "Please try again";
                        showNotification('bg-danger', "Item cannot be added, " + msg);

                    });
            }
        }
        $(document).ready(function() {
            $('#item_id').html(
                items.map(o => `<option value="${o.id}"> ${o.title}</option>`).join('')
            );
            $('#target_item_id').html(
                manufacturedItems.map(o => `<option value="${o.id}"> ${o.title}</option>`).join('')
            );

            $('#data').html(
                connectedItems.map(o => render(o)).join('')
            );

            $('#addItem').submit(function(e) {
                e.preventDefault();
                const ele = this;
                showProgress("Adding Item");
                axios.post(ele.action, new FormData(ele))
                    .then((res) => {
                        hideProgress();
                        showNotification('bg-success', "Item added successfully");
                        $('#data').append(
                            render(res.data)
                        );
                    }).catch((err) => {
                        hideProgress();
                        const msg = err.response ? err.response.data.message : "Please try again";
                        showNotification('bg-danger', "Item cannot be added, " + msg);

                    });

            });


        });
    </script>
@endsection
