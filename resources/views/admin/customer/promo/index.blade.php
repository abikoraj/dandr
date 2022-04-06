@extends('admin.layouts.app')
@section('title','Promo Message')
@section('css')
<link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
@endsection
@section('head-title','Promo Message')
@section('toobar')

@endsection
@section('content')
<div class="p-3">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                @foreach ($centers as $center)
                <div class="col-md-2" >
                    <input type="checkbox" value="{{$center->id}}" name="center_{{$center->id}}" id="center_{{$center->id}}" class="center" checked> {{$center->name}}
                </div>
                @endforeach
            </div>
            <hr>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn w-100 btn-primary" onclick="loadData()">
                Load Data
            </button>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button class="btn w-100 btn-danger" onclick="resetData()">
                Reset Data
            </button>
        </div>
    </div>
</div>
<div class="p-3 d-none" id="data-holder">
    <form action="{{route('admin.sms.promo')}}" method="POST" onsubmit="return sendSMS(event);">
        <textarea name="sms" id="sms" cols="30" rows="10" class="mb-2 form-control"></textarea>
        <div class="py-2">
            <button class="btn btn-sucess">Send Sms</button>
        </div>
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


            </tr>
           <tbody id="data">

           </tbody>
        </table>

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
            let centers=[];
            $('.center').each(function (index, element) {
                if(element.checked){
                    centers.push(element.value);
                }

            });
            if(centers.length==0){
                alert("Please Select At Least One Center");
                return;
            }
            working=true;
            showProgress("Loading Customers");
            axios.post('{{route('admin.customer.promo')}}',{centers:centers})
            .then((res)=>{
                console.log(res.data);
                let html="";
                res.data.forEach(cus => {
                    html+="<tr>"+
                        "<td><input class='selectable' type='checkbox' value='"+{{env('smstest',false)?'9800916365':'cus.phone'}}+"'></td>"+
                        "<td>"+cus.name+"</td>"+
                        "<td>"+cus.address+"</td>"+
                        "<td>"+cus.phone+"</td>"+
                        "</tr>";
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

    function resetData(){
        $('#data-holder').addClass("d-none");
    }

    function sendSMS(e){
        e.preventDefault();
        eles=document.querySelectorAll('.selectable:checked');
        const msg=$('#sms').val();
        if(msg==''){
            alert("Please Enter Message");
            return;
        }

        if(eles.length>0){

            let subids=[];
            let subids_texts=[];
            let subids_index=0;
            let subids_counter=0;

            for (let index = 0; index < eles.length; index++) {
                const id = eles[index].value;
                if(subids[subids_index]==undefined){
                    subids[subids_index]=[];
                }
                subids[subids_index].push(id);
                subids_counter+=1;
                if(index!=0 && subids_counter==50){
                    subids_texts.push(  subids[subids_index].join(','));
                    subids_index+=1;
                    subids_counter=0;
                }else{

                    if(index==(eles.length-1)){
                        subids_texts.push(  subids[subids_index].join(','));

                    }
                }
            }



            if(!working){
                working=true;
                showProgress("Sending SMS");
                axios.post('{{route('admin.sms.promo')}}',{
                    msg:msg,
                    phones:subids_texts
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
