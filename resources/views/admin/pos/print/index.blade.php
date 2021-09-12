@extends('admin.layouts.app')
@section('title', 'POS Bill RePrint')

@section('head-title', 'POS Bill RePrint')
@section('toobar')
    {{-- <a class="btn btn-primary" onclick="$('#addBill').addClass('shown');">Add Bill</a> --}}

@endsection
@section('content')
    <div class="pt-2 pb-2">
        @include('admin.layouts.daterange')
    </div>
    <div class="row ">
        <div class="col-md-3">
            <label for="">Bill no</label>
            <input type="text" name="bill_no" id="bill_no" class="form-control">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary" onclick="loadData()">Load Data</button>
        </div>
    </div>
    <hr>
    <div class="pt-2 pb-2">
        <div class="row">
            <div class="col-6">

                <input type="text" id="sid" placeholder="Search">
            </div>
            <div class="col-6 text-right">
                <span style="background: #0D6A9C;padding:8px 12px;">

                    @include('pos.layout.print')
                </span>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="" class="table table-bordered table-striped table-hover js-basic-example dataTable">
            <thead>
                <tr>
                    <th>Bill No.</th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    {{-- <th></th> --}}
                    <th>Total (Rs.)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="billData">

            </tbody>
        </table>
    </div>
    <a href="" id="xxx_52" class="d-none" target="_blank">click</a>

@endsection
@section('js')
<script src="{{asset('backend/js/signalr.js')}}"></script>
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/pages/forms/advanced-form-elements.js') }}"></script>
<script src="{{asset('backend/js/print.js')}}"></script>
    <script>
        const printBillURL = '{{ route('pos.billing.print',['bill'=>'__xx__']) }}';
        const printedBillURL = '{{ route('pos.billing.printed') }}';

        var customer_id=-1;
        var customer_id=-1;
        function loadData() {

            var data={
                'year':$('#year').val(),
                'month':$('#month').val(),
                'session':$('#session').val(),
                'week':$('#week').val(),
                'center_id':$('#center_id').val(),
                'date1':$('#date1').val(),
                'date2': $('#date2').val(),
                'type':$('#type').val(),
                'fy':$('#fiscalyear').val(),
                "customer_id":customer_id,
                'print':1,
                "bill_no":$('#bill_no').val()
            };
            axios({
                    method: 'post',
                    url: '{{ route('admin.pos.billing.index') }}',
                    data:data
                })
                .then(function(response) {
                    // console.log(response.data);
                    $('#billData').html(response.data);
                    initTableSearch('sid', 'billData', ['name', 'billno']);
                })
                .catch(function(response) {
                    //handle error
                    console.log(response);
                });
        }

        function loadDetail(id,billno){
            win.showPost("Bill Detail - " + billno, '{{ route('admin.pos.billing.detail') }}', {
                "id": id
            })
        }
        function initPrint(id,billno){
            showProgress('Printing');
            if(printSetting.type==0){
                url = printBillURL.replace("__xx__", id);
                newTab(url);
                hideProgress();
                printSetting.queue = false;
            }else{
                axios.post('{{route('admin.pos.billing.print.info')}}',{"id":id})
                .then((res)=>{
                    printSetting.print(res.data);
                })
                .catch((err)=>{
                    hideProgress();
                });

            }
        }

        window.onload = function() {
            $('#type').val(6).change();
            printSetting.init();
        };

    </script>
@endsection
