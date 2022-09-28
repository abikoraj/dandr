@extends('admin.layouts.app')
@section('title','Farmer - switch')
@section('head-title')
    <a href="{{route('admin.farmer.list')}}">Farmers</a>
    / On-Off
@endsection
@section('css')
@endsection
@section('toobar')
@endsection
@section('content')
<div class="row">

    <div class="col-md-3 text-right"><div class="mt-2"><strong> Collection Center : </strong></div></div>
    <div class="col-md-6">
        <div class="form-group text-right">
            <select name="center_id" id="center_id" class="form-control show-tick ms next" data-next="session">
                <option>Select A Collection Center</option>
                @foreach($centers as $c)
                    <option value="{{$c->id}}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-3">
        <button class=" btn btn-primary w-100" onclick="loadData();">
            Load
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
            axios.post('{{route('admin.farmer.switch')}}',{center_id:$('#center_id').val()})
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
