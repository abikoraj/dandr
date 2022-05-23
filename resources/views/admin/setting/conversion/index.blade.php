@extends('admin.layouts.app')
@section('title', 'Units')
@section('head-title', 'Units')
@section('css')
    <style>

        .conversion {
            border: 1px solid #aaaaaa;

        }
        .conversion.sub{
            border-bottom: none;
            border-right: none;
        }

    </style>
@endsection
@section('toobar')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="shadow">
                <div class="card-body">
                    <form action="{{ route('admin.setting.conversion.add') }}" method="post">
                        @csrf

                        <div>
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control">
                        </div>
                        <div class="div pt-2">
                            <button class="btn btn-primary w-100">
                                Add New Unit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            @foreach ($conversions->where('is_base', 1) as $conversion)
                <div class="conversion  pl-2 pt-2" id="conversion-{{ $conversion->id }}">
                    <div class="mb-0">
                        <div class="row">
                            <div class="col-md-6">
                                <form action="{{ route('admin.setting.conversion.update') }}" class="updateUnit"
                                    id="updateUnitForm-{{ $conversion->id }}">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $conversion->id }}">
                                    <input type="text" value="{{ $conversion->name }}" name="name"
                                        id="name-{{ $conversion->id }}" class="form-control">

                                </form>

                            </div>
                            <div class="col-md-6 text-right">
                                <span class="btn btn-success"
                                    onclick="initAddSubUnit({{ $conversion->id }},'{{ $conversion->name }}')">
                                    +
                                </span>
                                <button class="btn btn-primary" onclick="updateData({{ $conversion->id }})">
                                    u
                                </button>
                                @if ($conversion->used == 0)
                                    <button class="btn btn-danger" onclick="deleteData({{ $conversion->id }})">
                                        u
                                    </button>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div id="conversion-{{ $conversion->id }}-child" class="pt-2">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>name</strong>
                            </div>
                            <div class="col-md-5">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>
                                            Local Qty
                                        </strong>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>
                                            {{$conversion->name}} Qty
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @foreach ($conversions->where('parent_id', $conversion->id) as $item)
                            @include('admin.setting.conversion.subunitsingle', [
                                'conversion' => $item,
                                'baseUnit' => $conversion->name,
                                'old' => true,
                            ])
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @include('admin.setting.conversion.subunit')
@endsection
@section('js')
    <script>
        function updateSubUnitData(id) {
            showProgress('Updating Unit');
            axios.post('{{ route('admin.setting.conversion.update.sub') }}',
                    new FormData(document.getElementById('updateSubUnitForm-' + id)))
                .then((res) => {
                    hideProgress();
                })
                .catch((err) => {
                    hideProgress();
                })
        }


        function updateData(id) {
            showProgress('Updating Unit');

            axios.post('{{ route('admin.setting.conversion.update') }}',
                    new FormData(document.getElementById('updateUnitForm-' + id)))
                .then((res) => {
                    hideProgress();
                })
                .catch((err) => {
                    hideProgress();

                })
        }
        $(document).ready(function() {
            $('.updateUnit').submit(function(e) {
                e.preventDefault();
                showProgress('Updating Unit');
                axios.post('{{ route('admin.setting.conversion.update') }}', new FormData(this))
                    .then((res) => {
                        hideProgress();
                    })
                    .catch((err) => {
                        hideProgress();

                    })
            });
        });

        function deleteData(id) {
            if (prompt("Enter yes to delete") == 'yes') {

                showProgress('Deleting Unit');
                axios.post('{{ route('admin.setting.conversion.del') }}',{id:id})
                    .then((res) => {
                        hideProgress();
                        $('#conversion-'+id).remove();
                    })
                    .catch((err) => {
                        hideProgress();

                    })
            }

        }
    </script>
@endsection
