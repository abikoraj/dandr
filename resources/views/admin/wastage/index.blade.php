@extends('admin.layouts.app')
@section('title', 'Wastage')
@section('css')
    <link rel="stylesheet" href="{{asset('backend/plugins/select2/select2.css')}}">
@endsection

@section('head-title')
Wastage
@endsection
@section('content')
@include('admin.wastage.add')
<div class="shadow">
    <div class="card-body">
        @include('admin.layouts.daterange')
        <div class="row mt-2">
            <div class="col-md-3">
                <label for="center">Center</label>
                <select name="center_id" id="center_id" class="form-control ms">
                    @foreach ($centers as $center)
                        <option value="{{$center->id}}">{{$center->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 pt-1 pt-md-4">
                <button class="btn btn-success">Load Data</button>
            </div>
        </div>
    </div>
</div>


@endsection
@section('js')
        <script src="{{asset('backend/plugins/select2/select2.min.js')}}"></script>
        <script>
            const items={!!json_encode($items)!!};

            $(document).ready(function () {
                console.log(items);
                const itemOptionDatas=`<option></option>`+ (items.map(o=>{
                    return `<option value="${o.id}">${o.title}</option>`;
                }).join(''));
                $('#item_id').html(itemOptionDatas);
                $('#item_id').select2();
            });
        </script>
@endsection
