@extends('admin.layouts.app')
@section('title', 'Employee Chalan - Edit')
@section('head-title')
    <a href="{{ route('admin.chalan.index') }}">Employee Chalans</a>
    / {{ $chalan->name }} / {{ _nepalidate($chalan->date) }} / Edit
@endsection
@section('css')
@endsection
@section('content')

    <div class="shadow p-3 mb-3">


        <div id="print-detail">
            <style>
                .table-bordered td,
                .table-bordered th {
                    border: 1px solid black !important;
                }
            </style>
            <table class="table table-bordered">
                <tr>
                    <th>Item</th>
                    <th>Rate</th>
                    <th>Qty</th>
                    <th></th>
                </tr>
                @foreach ($items as $item)
                    <tr id="chalan-items-{{ $item->id }}">
                        <th>{{ $item->title }}</th>
                        <th>
                            <input type="number" id="rate_{{ $item->id }}" class="form-control"
                                value="{{ $item->rate }}">
                        </th>
                        <th>
                            <input type="number" id="qty_{{ $item->id }}" class="form-control"
                                value="{{ $item->qty }}">
                        </th>
                        <th>
                            @if (auth_has_per('15.08'))
                            <button class="btn btn-success"
                                onclick="update({{ $item->id }},'{{ route('admin.chalan.manage.edit', ['id' => $item->id]) }}')">
                                Update
                            </button>
                            @endif
                            @if (auth_has_per('15.09'))
                                <button class="btn btn-danger" onclick="del({{ $item->id }})">
                                    Delete
                                </button>
                            @endif

                        </th>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>

@endsection
@section('js')
    <script>
        @if (auth_has_per('15.08'))

            function update(id, url) {
                if (yes()) {
                    showProgress('Updating Data');
                    axios.post(url, {
                            qty: $('#qty_' + id).val(),
                            rate: $('#rate_' + id).val(),
                        })
                        .then((res) => {
                            successAlert('Update complete');
                        })
                        .catch((err) => {
                            errAlert(err);
                        });
                }
            }
        @endif
        @if (auth_has_per('15.09'))

            function del(id) {
                if (yes()) {
                    showProgress('Deleting Data');
                    axios.post('{{ route('admin.chalan.manage.del') }}', {
                            id: id
                        })
                        .then((res) => {
                            $('#chalan-items-' + id).remove();
                            successAlert('Delete complete');
                        })
                        .catch((err) => {
                            errAlert(err);
                        });
                }
            }
        @endif
    </script>
@endsection
