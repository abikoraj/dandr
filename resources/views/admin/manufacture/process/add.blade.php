@extends('admin.layouts.app')
@section('title')
    Manufacture Process - add
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
<form action="{{ route('admin.manufacture.process.add') }}" method="post" onsubmit="return addProcess(event,this);">
    <div class="row">
        <div class="col-md-4">
            <div class="shadow p-2">
                    @csrf
                    @if ($multiStock)

                    <div class="form-group">
                        <label for="center_id">Manufactring Plant / Branch</label>
                        <select name="center_id" id="center_id" class="form-control ms">
                            @foreach ($centers as $center)
                                <option value="{{$center->id}}">{{$center->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="manufactured_product_id">Product</label>
                        <select name="manufactured_product_id" id="manufactured_product_id" class="form-control ms "
                            onchange="productChanged(1);">

                        </select>
                    </div>
                    <div id="info" style="display: none;">

                        <div class="form-group">
                            <label for="expected">Expected Amount</label>
                            <input type="hidden" name="conversion_id" id="conversion_id">
                            <input type="number"  onchange="removeStockMessage()" oninput="renderTemplate()" name="expected" id="expected" step="0.001" class="form-control" value="1" required>
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
                        <div class="form-group">
                            <label for="stage">Starting Stage</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="radio" name="stage" id="stage-1" value="1" checked> Pending
                                </div>
                                <div class="col-6">
                                    <input type="radio" name="stage" id="stage-2" value="2"> Processing
                                </div>

                            </div>
                        </div>
                        <hr>
                        <div>
                            <button class="btn btn-primary w-100" onclick="return prompt('Enter yes to continue process')=='yes';">Add Process</button>
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
        const products = {!! json_encode($products) !!}
        const centers = {!! json_encode($centers) !!}
        var templates = [];
        const template = $('#template').html();
        var itemCenters=[];
        var product = null;

        function itemCenterChange(id,ele){
            itemCenters['item_'+id]=$(ele).val();
        }
        function productChanged(from) {
            const pid = $('#manufactured_product_id').val();
            product = products.find(o => o.id == pid);
            if (product == null || product == undefined) {
                return;
            }
            const currentDate = new Date($('#start').val());
            const expectedDate = new Date(currentDate.valueOf() + product.finish_ms);
            $('#conversion_id').val(product.conversion_id);
            $('#expected_end').val(getDateTimeLocal(expectedDate));
            console.log(currentDate, expectedDate);
            $('#info').show();
            if (from == 1) {
                loadTemplate();
            }
        }

        function saveProcess(ele){

            showProgress('Adding Manufacturing Process');
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
            if($('#stage-1')[0].checked){
                saveProcess(ele);

            }else{

                showProgress('checking Manufacturing Process Raw Material Stock');
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

        function renderTemplate() {
            console.log(templates,1);
            const center_id=$('#center_id').val();
            const qty = $('#expected').val();
            console.log(templates);
            $('#items').html(templates.map(function(data) {
                try {
                    html = template.replaceAll('xxx_id', data.id);
                    html = html.replaceAll('xxx_amount', data.amount * qty);
                    html = html.replaceAll('xxx_title', data.title);
                    @if ($multiStock)

                        if(itemCenters['item_'+data.id]==undefined){
                            itemCenters['item_'+data.id]=center_id;
                        }
                        const centerOptions=centers.map(o=> "<option value='"+o.id+"' "+ (itemCenters['item_'+data.id]==o.id?"selected":"") +">"+o.name+"</option>").join('');
                        console.log(centerOptions);
                        html = html.replaceAll('xxx_center', centerOptions);
                    @endif

                    return html;
                } catch (error) {
                    console.log(error);
                    return '';
                }
            }));

            // center_id
        }


        $(document).ready(function() {

            $('#manufactured_product_id').html(
                "<option></option>" + products.map(product => "<option value='" + product.id + "'>" + product
                    .title + "</option>")
            );
            $('#manufactured_product_id').select2();
            const currentDate = getDateTimeLocal(new Date());
            $('#start').val(currentDate);
        });
    </script>
@endsection
