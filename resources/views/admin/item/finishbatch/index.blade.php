@extends('admin.layouts.app')
@section('content')
<div class="row">
    <div class="col-md-3">
        <label for="item_id">Items</label>
        <select name="item_id" id="item_id" class="form-control ms">

            @foreach ($items as $item)
            <option value="{{$item->id}}">
                {{$item->title}}
            </option>
            @endforeach
        </select>
        
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <button class="btn btn-primary w-100" onclick="loadData()">
            Load Data
        </button>
    </div>
</div>

<hr>
<div id="allData">

</div>
        
@endsection

@section('js')
    <script>
        const batchURL='{{route('admin.simple.manufacture.batches',['id'=>'xxx_id'])}}';
        var batches=[];
      
        function loadData(){
            axios.post('{{route('admin.item.batch.finished.index')}}',{
                item_id:$('#item_id').val()
            })
            .then((res)=>{
                $('#allData').html(res.data);
                axios.get(batchURL.replace('xxx_id',$('#item_id').val()))
                .then((res)=>{
                    batches=res.data.data;
                    html+=""
                    .forEach(element => {
                        
                    });
                    console.log(res.data);
                });
            });
        }
    </script>
@endsection