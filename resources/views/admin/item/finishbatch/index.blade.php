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
        var multiple=false;
        function checkMultiBatch(ele){
            multiple=ele.checked;
            if(multiple){
                $('.to_batch_id_holder').removeClass('d-none');
                $('#batch_id_label').html('From Batch');
            }else{
                $('.to_batch_id_holder').addClass('d-none');
                $('#batch_id_label').html('Batch');
            }
        }

        function loadData(){
            axios.post('{{route('admin.item.batch.finished.index')}}',{
                item_id:$('#item_id').val()
            })
            .then((res)=>{
                $('#allData').html(res.data);
                axios.get(batchURL.replace('xxx_id',$('#item_id').val()))
                .then((res)=>{
                    batches=res.data.data;
                    $('#batch_id').html(batches.map(o=>`<option value="${o.batch_id}">${o.batch_no}</option>`).join(''));
                    $('#to_batch_id').html(batches.reverse().map(o=>`<option value="${o.batch_id}">${o.batch_no}</option>`).join(''));
                    multiple=false;
                    console.log(res.data);
                });
            });
        }

        function loadInfo(){
            const batch_id=parseInt($('#batch_id').val());
            const to_batch_id=parseInt($('#to_batch_id').val());
            const item_id=parseInt($('#item_id').val());
            if(multiple){
                if(batch_id==to_batch_id){
                    alert('Same Batch are selected');
                    return;
                }

                if(batch_id>to_batch_id){
                    alert('Starting batch is newer than ending batch');
                    return;
                }
            }
            var data={
                type:multiple?'multiple':'single',
                batch_id:batch_id,
                to_batch_id:to_batch_id,
                item_id:item_id
            };
            axios.post('{{route('admin.item.batch.finished.info')}}',data)
            .then((res)=>{
                $('#info').html(res.data);
            });
        }
    </script>
@endsection