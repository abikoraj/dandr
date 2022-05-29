@extends('admin.layouts.app')
@section('title')
    Manufacture Process - Edit
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
@endsection
@section('head-title')
    <a href="{{ route('admin.manufacture.process.index') }}">Manufacture Process</a> / add
@endsection
@section('toobar')
@endsection
@section('content')
<form action="{{ route('admin.manufacture.process.edit',['id'=>$process->id]) }}" method="post" onsubmit="return addProcess(event,this);">
    <div class="row">
        <div class="col-md-4">
            <div class="shadow p-2">
                    @csrf
                    @if ($multiStock)

                    <div>
                        <label for="" class="mb-0">Center / Branch</label> <br>
                        {{$process->center}}
                        <hr class="my-1">
                    </div>
                    @endif
                    <div>
                        <label for="" class="mb-0">Product</label> <br>
                        {{$process->title}}
                    </div>
                    <hr>
                    <div id="info">

                        <div class="form-group">
                            <label for="expected">Expected Amount</label>
                            <input type="hidden" name="conversion_id" id="conversion_id">
                            <input type="number"  onchange="removeStockMessage()" oninput="renderTemplate(this)" name="expected" id="expected" step="0.001" class="form-control" value="{{$process->expected}}" required>
                        </div>
                        <div class="form-group">
                            <label for="start">Start Datetime </label>
                            <input type="datetime-local" onchange="productChanged(2)" name="start" id="start"
                                class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="expected_end">Expected finish Datetime</label>
                            <input type="datetime-local" name="expected_end" id="expected_end" class="form-control" required>
                        </div>

                        <div>
                            <button class="btn btn-primary w-100" onclick="return prompt('Enter yes to update process')=='yes';">Update Process</button>
                        </div>
                    </div>


            </div>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-4  d-flex align-items-end">
                    <strong>Item</strong>

                </div>
                <div class="col-md-4 d-flex align-items-end" >
                    <strong>
                        Amount
                    </strong>
                </div>
                @if ($multiStock)

                <div class="col-md-4">
                    <strong>Branch Raw material used</strong>

                </div>
                @endif

            </div>
            <hr>
            <div id="items">
                @foreach ($items as $item)
                    <div class="row">
                        <div class="col-md-4">
                            {{$item->title}}
                        </div>
                        <div class="col-md-4">
                            <input type="hidden" name="item_id" value="{{$item->id}}">
                            <input type="number" step="0.001" value="{{$item->amount}}" name="item_{{$item->id}}" id="item_{{$item->id}}" class="form-control" required >
                        </div>
                        <div class="col-md-4">
                            {{$item->center}}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</form>
    <span class="d-none" id="template">

        <div id="item_xxx_id">
            <div class="row">
                <div class="col-md-4">
                    <strong>xxx_title</strong>
                    <div id="stock_check_xxx_id" class="stock_check text-danger">

                    </div>
                </div>
                <input type="hidden" name="manufactured_product_item_ids[]" value="xxx_id">
                <div class="col-md-4">
                    <input step="0.001" onchange="removeStockMessage()" type="number" value="xxx_amount" name="amount_xxx_id" id="amount_xxx_id"
                        class="form-control">
                </div>
                @if ($multiStock)

                <div class="col-md-4">
                    <select onchange="itemCenterChange(xxx_id,this)" name="center_id_xxx_id" id="center_id_xxx_id" class="form-control ms">xxx_center</select>
                </div>
                @endif

            </div>
            <hr>

        </div>
    </span>
@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script>
        const process={!! json_encode($process)!!};
        const centers = {!! json_encode($centers) !!};
        const items = {!! json_encode($items) !!};

        var templates = [];
        const template = $('#template').html();
        var itemCenters=[];
        var product = null;

        function itemCenterChange(id,ele){
            itemCenters['item_'+id]=$(ele).val();
        }
        function productChanged(from) {

            const currentDate = new Date($('#start').val());
            const expectedDate = new Date(currentDate.valueOf() + process.finish_ms);
            $('#expected_end').val(getDateTimeLocal(expectedDate));

        }

        function saveProcess(ele){
            axios.post('{{ route('admin.manufacture.process.add') }}',new FormData(ele))
                .then((res) => {
                   window.location.reload();
                })
                .catch((err) => {
                    hideProgress();
                    alert('Process cannot be added please try again.');

                });
        }

        function removeStockMessage(){
            $('.check_stock').html('');

        }

        function addProcess(e,ele){
            removeStockMessage();
            e.preventDefault();
                showProgress('Adding Manufacturing Process');
                axios.post('{{ route('admin.manufacture.process.check.stock') }}',new FormData(ele))
                .then((res) => {
                    const data=res.data;
                    if(data.hasstock){
                        saveProcess(ele);
                    }else{
                        data.msgs.forEach(msg => {
                            $('#stock_check_'+msg.id).html('Not Enough Stock');
                        });
                        hideProgress();

                    }
                })
                .catch((err) => {
                    hideProgress();
                });
        }

        function loadTemplate() {
            axios.post('{{ route('admin.manufacture.process.load.template') }}', {
                    id: product.id
                })
                .then((res) => {
                    templates = res.data;
                    renderTemplate();
                    console.log(res.data);
                })
                .catch((err) => {

                });
        }

        function renderTemplate(ele) {
            const qty=ele.value;
            items.forEach(item => {
                $('#item_'+item.id).val((qty*item.item_amount).toFixed(3));
            });


        }


        $(document).ready(function() {

            const currentDate = new Date('{{$process->start}}');
            const expectedDate = new Date('{{$process->expected_end}}');
            $('#start').val(getDateTimeLocal(currentDate));
            $('#expected_end').val(getDateTimeLocal(expectedDate));
            console.log(items);
        });
    </script>
@endsection
