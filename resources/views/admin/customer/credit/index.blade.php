@extends('admin.layouts.app')
@section('title','Customer Credit List')
@section('css')
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
@endsection
@section('head-title','Customer Credit List')
@section('toobar')

@endsection
@section('content')
<div class="p-3">
    <div class="row">
        <div class="col-md-4">
            <label for="before">Last Payment Before</label>
            <input type="text" class="calender form-control" id="date">
        </div>
        <div class="col-md-2 d-flex align-items-end">

            <button class="btn w-100 btn-primary" onclick="loadData()">
                Load Data
            </button>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn w-100 btn-danger">
                Reset Data
            </button>
        </div>
    </div>
</div>
<div class="p-3 d-none" id="data-holder">
    <form action="{{route('admin.sms.distributer.credit')}}" method="POST" onsubmit="return sendSMS(event);">
        @csrf
        <table class="table table-bordered">
            <tr>
                <th>
                    <input type="checkbox"  onchange="selectChange(this)">
                </th>
                <th>Name</th>
                <th>
                    Address
                </th>
                <th>
                    Phone
                </th>
                <th>
                    Credit (Rs.)
                </th>
                <th>
                    last  Payment
                </th>
                <th>
                    Last SMS
                </th>

            </tr>
           <tbody id="data">

           </tbody>
        </table>
        <div class="py-2">
            <button class="btn btn-sucess">Send Sms</button>
        </div>
    </form>
</div>
@endsection
@section('js')
<script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/pages/forms/advanced-form-elements.js') }}"></script>
<script>
    var working=false;
    function selectChange(ele){
        $('.selectable').each(function(){
            this.checked=ele.checked;
        });
    }

    function loadData(){
        if(!working){
            working=true;
            showProgress("Loading Credit Data");
            axios.post('{{route('admin.customer.credit-list.index')}}',{date:$('#date').val()})
            .then((res)=>{
                console.log(res.data);
                let html="";
                res.data.forEach(cus => {
                    html+="<tr>"+
                        "<td><input class='selectable' type='checkbox' value='"+cus.id+"'></td>"+
                        "<td>"+cus.name+"</td>"+
                        "<td>"+cus.address+"</td>"+
                        "<td>"+cus.phone+"</td>"+
                        "<td>"+cus.due+"</td>";
                        if(cus.latestPay==0){
                            html+="<td>No Record</td>";
                        }else{
                           html+= "<td>"+toNepaliDate(cus.latestPay)+"</td>";
                        }
                        if(cus.last=="N/A"){
                            html+= "<td>N/A</td>";
                        }else{
                            html+= "<td>"+cus.last +" Days</td>";
                        }

                        html+="</tr>";
                });
                $('#data').html(html);
                $('#data-holder').removeClass("d-none");
                working=false;
                hideProgress();
            })
            .catch((err)=>{
                console.log(err);
                working=false;
                hideProgress();

            })
        }
    }

    function sendSMS(e){
        e.preventDefault();
        eles=document.querySelectorAll('.selectable:checked');
        let ids=[];
        if(eles.length>0){
            for (let index = 0; index < eles.length; index++) {
                const element = eles[index];
                ids.push(element.value);

            }
            if(!working){
                working=true;
                showProgress("Sending SMS");
                axios.post('{{route('admin.sms.customer.credit')}}',{
                    ids:ids
                })
                .then((res)=>{
                    console.log(res.data);
                    hideProgress();
                    working=false;
                    showNotification('bg-success',"SMS sent successfully");
                })
                .catch((err)=>{
                    hideProgress();
                    showNotification('bg-danger',"Some error occured please Try again");
                    working=false;

                })
            }

        }else{
            alert('Please Select At least One Customer');
            return false;
        }
    }
    function check(){
        for (let index = 0; index < eles.length; index++) {
            const element = eles[index];
            if(element.checked){
                return true;
            }
        }
        alert('Please Select At least One Customer');
        return false;
    }
</script>
@endsection
