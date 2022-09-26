@extends('admin.layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-3">
            <label for="item_id">Items</label>
            <select name="item_id" id="item_id" class="form-control ms">

                @foreach ($items as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->title }}
                    </option>
                @endforeach
            </select>

        </div>
        <div class="col-md-3">
            <label for="multi_batch">
                <input type="checkbox" name="multi_batch" id="multi_batch">
                Multiple Batch
            </label>
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
        const batchURL = '{{ route('admin.simple.manufacture.batches', ['id' => 'xxx_id']) }}';
        var batches = [];
        var multiple = false;

        function checkMultiBatch(ele) {
            multiple = ele.checked;
            if (multiple) {
                $('.to_batch_id_holder').removeClass('d-none');
                $('#batch_id_label').html('From Batch');
            } else {
                $('.to_batch_id_holder').addClass('d-none');
                $('#batch_id_label').html('Batch');
            }
        }

        function loadData() {
            axios.post('{{ route('admin.item.batch.finished.index') }}', {
                    item_id: $('#item_id').val(),
                    multiple: $('#multi_batch')[0].checked ? 1 : 0
                })
                .then((res) => {
                    $('#allData').html(res.data);
                    axios.get(batchURL.replace('xxx_id', $('#item_id').val()))
                        .then((res) => {
                            batches = res.data.data;
                            $('#batch_id').html(batches.map(o =>
                                `<option value="${o.batch_id}">${o.batch_no}</option>`).join(''));
                            $('#to_batch_id').html(batches.reverse().map(o =>
                                `<option value="${o.batch_id}">${o.batch_no}</option>`).join(''));
                            multiple = false;
                            console.log(res.data);
                        });
                });
        }

        function loadInfo() {
            let batch_id = parseInt($('#batch_id').val());
            let to_batch_id = parseInt($('#to_batch_id').val());
            const item_id = parseInt($('#item_id').val());
            let type = 'single';
            if ($('#multi_batch')[0].checked) {

                const combo = $('#multiple_batch_id').val();
                const batchDatas=combo.split("|");
                console.log(combo,batchDatas);
                batch_id =batchDatas[0];
                to_batch_id =batchDatas[1];
                type = 'multiple';

            }
            var data = {
                type: type,
                batch_id: batch_id,
                to_batch_id: to_batch_id,
                item_id: item_id
            };
            axios.post('{{ route('admin.item.batch.finished.info') }}', data)
                .then((res) => {
                    $('#info').html(res.data);
                });
        }

        function addBatchFinish() {
            if(prompt('Enter yes to continue')=='yes'){
                showProgress('Adding batch');
                const ele=document.getElementById('addFinishedBatch');
                axios.post(ele.action,new FormData(ele))
                .then(res=>{
                    hideProgress();
                    showNotification('bg-success','Batch finish added successfully');
                    loadData();
                })
                .catch((err)=>{
                    const msg=err.response?err.response.data.message:'Please try again';
                    hideProgress();
                    showNotification('bg-danger','Batch could no be added,'+msg);
                });
            }
        }
    </script>
@endsection
