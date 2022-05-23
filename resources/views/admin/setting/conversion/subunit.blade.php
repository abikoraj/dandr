<div class="modal fade" id="addSubUnitModal" tabindex="-1" role="dialog" data-ff="name">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Create New Unit</h4>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                    <form id="addSubUnitForm" action="{{ route('admin.setting.conversion.add.sub') }}" >
                        @csrf
                        <input type="hidden" name="parent_id" id="parent_id" value="" >
                        <input type="hidden" name="baseUnit" id="baseUnit" value="" >
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="name"> Name</label>
                                    <input type="text" id="name" name="name" class="form-control next" data-next="local" placeholder="Enter Unit name" required>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="local">Conversion Amount</label>
                                    <input type="number" step="0.001" name="local" id="local" data-next="main" class="form-control next" value="1" required>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="main" style="0.001" id="mainLabel"></label>
                                    <input type="number" name="main" id="main" class="form-control" value="1">
                                </div>
                            </div>

                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-raised btn-primary waves-effect" type="submit">Submit Data</button>
                <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Close</button>
            </div>
            </form>
        </div>
    </div>
</div>

@section('js1')

<script>
    var parent_id=0;
    function initAddSubUnit(id,name) {
        $('#parent_id').val(id);
        parent_id=id;
        $('#mainLabel').html(name);
        $('#baseUnit').html(name);
        $('#addSubUnitModal').modal('show');
    }

    $('#addSubUnitForm').submit(function(e){
        e.preventDefault();
        showProgress('Adding SubUnit');
        axios.post('{{route('admin.setting.conversion.add.sub')}}',new FormData(this))
        .then((res)=>{
            $('#addSubUnitModal').modal('hide');
            $('#conversion-'+parent_id+'-child').append(res.data);
            console.log(this);
            hideProgress();
        })
        .catch((err)=>{
            hideProgress();
            showNotification('bg-error','some Error Occured Please Try Again');
        });
    });
</script>
@endsection
