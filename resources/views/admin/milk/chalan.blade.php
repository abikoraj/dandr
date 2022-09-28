@extends('admin.layouts.app')
@section('content')

<div class="row">
    <div class="col-md-3">
        <label for="date">Date</label>
        <input type="text" class="form-control calender" name="date" id="date">
    </div>
    <div class="col-md-3 pt-4">
        <button class="btn btn-primary w-100" onclick="loadData()">
            Load Chalan
        </button>
    </div>
</div>
<div id="alldata"></div>
@endsection
@section('js')
    <script>
        function loadData(){
            showProgress("Loading Data");
            axios.post("{{route('admin.milk.chalan')}}",{"date":$('#date').val()})
            .then((res)=>{
                $('#alldata').html(res.data);
                hideProgress();
            })
            .catch((err)=>{
                hideProgress();

            });
        }

        function save(e,ele) {
            e.preventDefault();
            if(confirm("Do you want to save chalan?")){
                showProgress('Saving Chalan');
                axios.post(ele.action,new FormData(ele))
                .then((res)=>{
                    loadData();

                })
                .catch((err)=>{
                    hideProgress();
                })

            }
        }

        function loadAllChalan(){
            $('.milkdata').each(function (index, element) {
                element.click();

            });
        }

        function collect(id,amount){
            console.log(id,amount);
            $('#amount_'+id).val(amount);
        }
    </script>

@endsection
