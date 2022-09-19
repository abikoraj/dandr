@extends('admin.layouts.app')
@section('title', 'Items - Stock Outs')
@section('head-title')
    <a href="{{ route('admin.item.index') }}">Items</a> / Stock Tracking
@endsection
@section('toobar')
    @include('admin.layouts.daterange') <br>
    <div class="row">
        <div class="col-md-4">
            <select name="Item_id" id="item_id" class="form-control show-tick ms ">
                <option value="">Select Items</option>
                @foreach ($items as $item)
                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <span class="btn btn-primary" onclick="loadData();">Load Data</span>
        </div>
    </div>
@endsection
@section('content')

<div id="allData">

</div>

    <table class="">
        <th></th>
    </table>

@endsection
@section('js')
    <script>
        function loadData() {
            var d = {
                'year': $('#year').val(),
                'month': $('#month').val(),
                'session': $('#session').val(),
                'week': $('#week').val(),
                'item_id': $('#item_id').val(),
                'date1': $('#date1').val(),
                'date2': $('#date2').val(),
                'type': $('#type').val(),
            };

            if($('#item_id').val()==""){
                alert('Please select item');
                return false;
            }

            axios.post("{{ route('admin.item.stock.tracking') }}", d)
                .then(function(response) {
                    $('#allData').html(response.data);

                })
                .catch(function(error) {
                    alert('some error occured');
                });

        }

    </script>

@endsection
