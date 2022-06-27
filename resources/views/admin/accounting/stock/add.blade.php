<div class="shadow mb-3">
    <div class="p-3">
        <form action="{{route('admin.accounting.stock.add')}}" id="stockAddForm">
        @csrf
            <div class="row">
                <div class="col-md-3">
                    <label for="date">Date</label>
                    <input type="text" name="date" id="date" class="form-control calender">
                </div>
                <div class="col-md-3">
                    <label for="amount" class="d-flex justify-content-between">
                        <span>Amount</span>
                        <span><span class="btn-link p-0" onclick="getCurrent();"
                                data-value="{{ currentStock()->sum }}">Current</span></span>
                    </label>
                    <input type="number" name="amount" id="amount" class="form-control" step="0.01">
                </div>
                <div class="col-md-3">
                    <label for="type">Type</label>
                    <select name="type" id="add_type" class="ms form-control">
                        <option value="1">Opening</option>
                        <option value="2">Closing</option>
                    </select>
                </div>
                <div class="col-md-3 pt-4">
                    <button class="btn btn-primary">Add Stock Amount</button>
                </div>
            </div>
        </form>
    </div>
</div>
@section('js1')
    <script>
        function getCurrent() {

            axios.post('{{ route('current-stock') }}', {})
                .then((res) => {
                    $('#amount').val(res.data);
                })
        }

        $(document).ready(function () {
            $('#stockAddForm').submit(function(e){
                e.preventDefault();
                showProgress("loadingData");
                axios.post(this.action,new FormData(this))
                .then((res)=>{
                    hideProgress();
                    showNotification('bg-success','Stock Added Sucessfully');
                    $('#amount').val('');
                    $('#add-type').val(1).change();
                })
                .catch((Err)=>{
                    hideProgress();
                    showNotification('bg-error','Stock Cannot be Added, Please Try again');
                });
            });
        });
    </script>
@endsection
