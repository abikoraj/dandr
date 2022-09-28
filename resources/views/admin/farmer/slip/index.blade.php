@extends('admin.layouts.app')
@section('title','Farmer - switch')
@section('head-title')
    <a href="{{route('admin.farmer.list')}}">Farmers</a>
    / Print Slip
@endsection
@section('css')
@endsection
@section('toobar')
@endsection
@section('content')
<div class="row">

    <div class="col-md-3">

        <div class="form-group ">
            <label for="date">date</label>
            <input type="text" name="date" id="date" class="form-control show-tick ms next calender" />

        </div>
    </div>
    <div class="col-md-3">

        <div class="form-group ">
            <label for="center_id">Center</label>
            <select name="center_id" id="center_id" class="form-control show-tick ms next" data-next="session">
                @foreach($centers as $c)
                    <option value="{{$c->id}}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group ">
            <label for="type">Type</label>
            <select name="type" id="type" class="form-control show-tick ms next" data-next="session">
                    <option value="1">Milk Collection</option>
                    <option value="2">Fat Snf</option>
                    <option value="3"> Milk Collection + Fat Snf</option>
            </select>
        </div>
    </div>

    <div class="col-md-3 pt-4">
        <button class=" btn btn-primary " onclick="loadData();">
            Load
        </button>
        <button class=" btn btn-success " onclick="printDiv('allData');">
            print
        </button>
    </div>

</div>
<div id="allData">

</div>


@endsection
@section('js')
    <script>
        function loadData(){
            showProgress("Loading Farmer");
            axios.post('{{route('admin.farmer.printSlip')}}',{
                center_id:$('#center_id').val(),
                type:$('#type').val(),
                date:$('#date').val(),
            })
            .then((res)=>{
                $('#allData').html(res.data);
                hideProgress();
            })
            .catch((err)=>{
                hideProgress();
            })
        }

        function sel(farmer_id) {
            const checked=$('#checkbox_'+farmer_id)[0].checked;
            $('#checkbox_'+farmer_id)[0].checked=!checked;
        }

        function save() {
            const checked=[];
            const unchecked=[];
            document.querySelectorAll('.check-farmer').forEach(element => {
                if(element.checked){
                    checked.push(element.dataset.id);
                }else{
                    unchecked.push(element.dataset.id);
                }
            });

            showProgress("Saving Farmer");
            axios.post("{{route('admin.farmer.switchSave')}}",{checked:checked,unchecked:unchecked})
            .then((res)=>{hideProgress()})
            .catch((err)=>hideProgress());
        }
    </script>
@endsection
