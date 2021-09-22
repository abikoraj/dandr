@extends('admin.layouts.app')
@section('title')
Sales Return - Billno : {{$bill->bill_no}}
@endsection

@section('head-title')
<a href="{{route('admin.pos.billing.return')}}">Sales Return</a> / Billno : {{$bill->bill_no}}

@endsection
@section('toobar')
    {{-- <a class="btn btn-primary" onclick="$('#addBill').addClass('shown');">Add Bill</a> --}}

@endsection
@section('content')

    @include('admin.pos.return.init')

@endsection
@section('js')
<script src="{{asset('backend/js/signalr.js')}}"></script>
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/pages/forms/advanced-form-elements.js') }}"></script>
<script src="{{asset('backend/js/print.js')}}"></script>
    <script>
        const printBillURL = '{{ route('pos.billing.print',['bill'=>'__xx__']) }}';
        const printedBillURL = '{{ route('pos.billing.printed') }}';




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

        function returnChangeQty(ele){
            const data=JSON.parse(ele.dataset.billitem);
            console.log(data);
            if(data.qty<ele.value){
                ele.value=parseFloat( data.qty);
            }else if(ele.value<0){
                ele.value=0;
            }
            // calculateTotal();
        }



    </script>
@endsection
