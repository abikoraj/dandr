@extends('admin.layouts.app')
@section('css')
    <link rel="stylesheet" href="{{asset('print/main.css')}}">
@endsection
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

<div id="returnbill-wrapper" class="shadow">
    <h4 class="text-center mt-2">CREDIT NOTE</h4>
    <form  id="returnbill" onsubmit="return SubmitData(event,this)">
            @csrf
            @include('admin.pos.return.init')
        </form>
    </div>

    <div id="data-wrapper" class="shadow">
        <div id="print" class="text-right d-none px-5">
            <hr>
            <span style="background: #0D6A9C;padding:8px 12px;">

                @include('pos.layout.print')
            </span>
        </div>
        <div id="data" class="p-5 d-none">

        </div>
    </div>

@endsection
@section('js')
<script src="{{asset('backend/js/signalr.js')}}"></script>
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/pages/forms/advanced-form-elements.js') }}"></script>
<script src="{{asset('backend/js/print.js')}}"></script>
    <script>
        const printCreditNoteURL = '{{ route('admin.pos.billing.return.print',['note'=>'__xx__']) }}';
        // const printedBillURL = '{{ route('pos.billing.printed') }}';

        function initPrint(id){
            showProgress('Printing Credit Note : '+id);
            if(printSetting.type==0){
                url = printCreditNoteURL.replace("__xx__", id);
                newTab(url);
                hideProgress();
                printSetting.queue = false;
            }else{
                axios.post('{{route('admin.pos.billing.creditnote.info')}}',{"id":id})
                .then((res)=>{
                    printSetting.printCreditReturn(res.data);
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
            let _ok=true;
            e.preventDefault();
            $('.return-qty').each(function(){
                if(this.value>0){
                    _ok=false;
                }
            });
            if(_ok){
                alert('Please Return  At least One Item');
                return;
            }
            const data=new FormData(ele);
            axios.post('{{route('admin.pos.billing.return.init')}}',data).then((res)=>{
                $('#data').html(res.data);
                $('#data').html(res.data);
                $('#returnbill-wrapper').remove();
                $('#data-wrapper>div').removeClass('d-none');

            })
            .catch((err)=>{
                $('#data').html(err.response.message);

            });
            console.log(data);
        }

        function returnAll(){
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
     <script>
        function getTime(){
            var currentTime = new Date();
            var hours = currentTime.getHours();
            var minutes = currentTime.getMinutes();
            var seconds = currentTime.getSeconds();
            if (minutes < 10){
                minutes = "0" + minutes;
            }
            if (seconds < 10){
                seconds = "0" + seconds;
            }
            var v = hours + ":" + minutes + ":" + seconds + " ";
            if(hours > 11){
                v+="PM";
            } else {
                v+="AM"
            }
            return v;
            document.getElementById('time').innerText=v;
            setTimeout(function(){
                getTime();
            }, 1000);
        }

    </script>

@endsection
