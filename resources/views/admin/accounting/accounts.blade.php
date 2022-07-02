@extends('admin.layouts.app')
@section('title',"Accounts")
@section('head-title')
<a href="{{route('admin.accounting.index')}}">Accounting</a>
/ Accounts
@endsection
@section('content')
<div class="row">
    <div class="col-md-4">
        <label for="fical_year_id">Fiscal Year</label>
        <select name="fical_year_id" id="fical_year_id" class="form-control ms">
            @foreach ($fys as $fy)
                @if ($selectedfy!=null)
                    <option value="{{$fy->id}}" {{$fy->id==$selectedfy->id?"selected":""}}>{{$fy->name}}</option>
                @else
                    <option value="{{$fy->id}}" >{{$fy->name}}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="col-md-4 pt-4">
        <button class="btn btn-primary" onclick="loadAccounts()">Load Accounts</button>
    </div>
</div>
<hr>
<div id="data"></div>
@endsection
@section('js')
    <script>
        const addURL="{{route('admin.accounting.accounts.add',['parent_id'=>0,'type'=>'xxx_type'])}}";
        function addAccount(type){
            win.showGet(`Add ${type==1?'Asset':'Libility'} Account`,addURL.replace('xxx_type',type));
        }

        function loadAccounts() {
            showProgress("Loading Accounts");
            axios.post("{{route('admin.accounting.accounts')}}",{fiscal_year_id:$('#fical_year_id').val()})
            .then((res)=>{
                $('#data').html(res.data);
                hideProgress();
            })
            .catch((err)=>{
                hideProgress();
            });
        }

        function saveAccount(e,ele) {
            e.preventDefault();
            showProgress('Adding Account');
            axios.post(ele.action,new FormData(ele))
            .then((res)=>{

                window.location.reload();
            })
            .catch((err)=>{
                hideProgress();
                showNotification("bg-danger","Error : "+err.response.data.message)
            })

        }

        $(document).ready(function () {
            loadAccounts();
        });
    </script>

@endsection
