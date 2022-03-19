<div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-ff="name">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Create Counter</h4>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                    <form id="addCounter" method="POST" onsubmit="return saveData(event);">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="name">Counter Name</label>
                                <div class="form-group">
                                    <input type="text" id="name" name="name" class="form-control next" data-next="phone" placeholder="Enter Counter name" required>
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
        function saveData(e) {
            e.preventDefault();
            if(!lock){
                lock=true;
                showProgress('Adding Counter');
                var data=new FormData(document.getElementById('addCounter'));
                data.append('date',$('#day').val());
                axios.post('{{route('admin.counter.add')}}',data)
                .then((res)=>{
                    $('#data').append(res.data);
                    hideProgress();
                    lock=false;
                    $('#addModal').modal('hide');
                    document.getElementById('addCounter').reset();
                })
                .catch((err)=>{
                    hideProgress();
                    lock=false;
                })
            }
        }
    </script>
@endsection
