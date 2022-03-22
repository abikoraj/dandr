@extends('admin.layouts.app')
@section('title', 'Point Setting')

@section('head-title', 'Point Setting')

@section('content')
    <form action="{{ route('admin.point.index') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <label for="type">Point Type</label>
                <select onchange="changeType()" name="type" id="type" class="form-control ms">
                    <option value="0" {{ $point->type == 0 ? 'selected' : '' }}>Diabled</option>
                    <option value="1" {{ $point->type == 1 ? 'selected' : '' }}>Whole Bill Total</option>
                    <option value="2" {{ $point->type == 2 ? 'selected' : '' }}>Item Wise Point</option>
                </select>
            </div>
            <div class="col-md-3 type type-1">
                <label for="point">Point</label>
                <input type="number" class="form-control" min="0" required value="{{ $point->point }}" id="point"
                    name="point">
            </div>
            <div class="col-md-3 type type-1">
                <label for="per">Per</label>
                <input type="number" class="form-control" min="0" required value="{{ $point->per }}" id="per" name="per">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary w-100">Save Setting</button>
            </div>
        </div>
    </form>
@endsection
@section('js')
    <script>
        function changeType() {
            var type = $('#type').val();
            $('.type').addClass('d-none');
            $('.type-' + type).removeClass('d-none');
        }

        $(document).ready(function() {
            changeType();
        });
    </script>
@endsection
