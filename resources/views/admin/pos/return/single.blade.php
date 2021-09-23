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
    <h4 class="text-center">CREDIT NOTE</h4>
    <div class="text-right">
        <span class="btn btn-success" onclick="all();">Return all</span>
    </div>
    <hr>
    <form  id="returnbill" onsubmit="return SubmitData(event,this)">
        @csrf
        @include('admin.pos.return.init')
    </form>

    <div id="data" class="p-5">

    </div>
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

        function SubmitData(e,ele){
            let _ok=false;
            e.preventDefault();
            $('.return-qty').each(function(){
                if(this.value>0){
                    _ok=true;
                }
            });
            if(_ok){
                alert('Please Return  At least One Item');
                return;
            }
            const data=new FormData(ele);
            axios.post('{{route('admin.pos.billing.return.init')}}',data).then((res)=>{
                $('#data').html(res.data);
            })
            .catch((err)=>{
                $('#data').html(err.response.message);

            });
            console.log(data);
        }

        function all(){
            console.log('all');
            $('.return-qty').each(function(){
                this.value=JSON.parse(this.dataset.billitem).qty;

            });
            calculateTotal();
        }


        function calculateTotal(){
            let _removeList=[];
            $('.return-qty').each(function(){

                const data=JSON.parse(this.dataset.billitem);
                const _qty=this.value;
                const _amount=data.rate*_qty;
                const _discount=0;
                let _tax=0;
                const _taxable=_amount-_discount;
                if(data.use_tax==1 ){
                    _tax=((_taxable)*(data.tax_per)/100).toFixed(2);
                }
                const _total=(parseFloat(_tax)+_taxable).toFixed(2);
                $('#billitem-'+data.id+'-amount').html(_amount);
                $('#billitem-'+data.id+'-discount').html(_discount);
                $('#billitem-'+data.id+'-taxable').html(_taxable);
                $('#billitem-'+data.id+'-tax').html(_tax);
                $('#billitem-'+data.id+'-total').html(_total);
                console.log(this.dataset.billitem,this.value,"bill items");
            });
        }

    </script>
@endsection
