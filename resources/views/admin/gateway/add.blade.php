<div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-ff="name">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Create Bank</h4>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                    <form id="addGateway" method="POST" onsubmit="return saveData(event);">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="name">Name</label>
                                <div class="form-group">
                                    <input type="text" id="name" name="name" class="form-control next" data-next="public_key" placeholder="Enter Gateway Name" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="public_key">Public Key</label>
                                <div class="form-group">
                                    <input type="text" id="public_key" name="public_key" class="form-control next" data-next="private_key"   >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="private_key">Private Key</label>
                                <div class="form-group">
                                    <input type="text" id="private_key" name="private_key" class="form-control next" data-next="api_key"   >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="api_key">Api Key</label>
                                <div class="form-group">
                                    <input type="text" id="api_key" name="api_key" class="form-control"   >
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
                showProgress('Adding Gateway');
                var data=new FormData(document.getElementById('addGateway'));
                axios.post('{{route('admin.gateway.add')}}',data)
                .then((res)=>{
                    $('#data').append(res.data);
                    hideProgress();
                    lock=false;
                    $('#addModal').modal('hide');
                    document.getElementById('addGateway').reset();
                })
                .catch((err)=>{
                    hideProgress();
                    lock=false;
                })
            }
        }
    </script>
@endsection