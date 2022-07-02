<div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-ff="name">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Create Bank</h4>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                    <form id="addBank" method="POST" onsubmit="return saveData(event);">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="account_id" value="{{$account->id}}">
                            <div class="col-lg-6">
                                <label for="name">Bank Name</label>
                                <div class="form-group">
                                    <input type="text" id="name" name="name" class="form-control next" data-next="phone" placeholder="Enter Bank Name" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="phone">Phone No</label>
                                <div class="form-group">
                                    <input type="text" id="phone" name="phone" class="form-control next" data-next="phone" placeholder="Enter Bank Phone No" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label for="address">Address</label>
                                <div class="form-group">
                                    <input type="text" id="address" name="address" class="form-control next" data-next="phone" placeholder="Enter Bank Address" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="accno">Account No</label>
                                <div class="form-group">
                                    <input type="text" id="accno" name="accno" class="form-control next" data-next="phone" placeholder="Enter Bank Account No" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="balance">Balance</label>
                                <div class="form-group">
                                    <input type="number" min="0"  id="balance" name="balance" class="form-control next" data-next="phone" placeholder="Enter Bank Account No" required>
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
                showProgress('Adding bank');
                var data=new FormData(document.getElementById('addBank'));
                axios.post('{{route('admin.bank.add')}}',data)
                .then((res)=>{
                    $('#data').append(res.data);
                    hideProgress();
                    lock=false;
                    $('#addModal').modal('hide');
                    document.getElementById('addBank').reset();
                })
                .catch((err)=>{
                    hideProgress();
                    lock=false;
                })
            }
        }
    </script>
@endsection
