{{-- <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-ff="name">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Create Customer</h4>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                    <form id="addCustomer" method="POST" onsubmit="return saveData(event);">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="name">Customer Name</label>
                                <div class="form-group">
                                    <input type="text" id="name" name="name" class="form-control next" data-next="phone" placeholder="Enter customer name" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="name">Customer Phone</label>
                                <div class="form-group">
                                    <input type="number" id="phone" name="phone" class="form-control next" data-next="address" placeholder="Enter customer phone" required>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <label for="name">Customer Address</label>
                                <div class="form-group">
                                    <input type="text" id="address" name="address" class="form-control " data-next="panvat" placeholder="Enter customer address" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="name">Customer PAN/VAT</label>
                                <div class="form-group">
                                    <input type="text" id="panvat" name="panvat" class="form-control " data-next="date" placeholder="Enter customer PAN/VAT" >
                                </div>
                            </div>
                            <div class="col-12"></div>
                            <div class="col-lg-4">
                                    <label for="date">Opening Balance Date</label>
                                    <div class="form-group">
                                        <input type="text" id="date" name="date" min="0" step="0.01" class="form-control next calender" data-next="amount" placeholder="Enter Current Balance" required>
                                    </div>
                            </div>

                            <div class="col-lg-4">
                                <label for="amount">Opening Balance</label>
                                <div class="form-group">
                                    <input type="number" id="amount" name="amount" min="0" step="0.01" class="form-control next" data-next="address" placeholder="Enter Current Balance" required>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label for="amounttype">Balance Type</label>
                                <div class="form-group">
                                    <select name="amounttype" id="amounttype" class="form-control show-tick ms">
                                        <option value="1">CR</option>
                                        <option value="2">DR</option>
                                    </select>
                                </div>
                            </div>

                             <div class="col-lg-6">
                                <label for="rate">Rate</label>
                                <div class="form-group">
                                    <input type="number" id="rate" name="rate" step="0.001" min="0.001" class="form-control next" data-next="amt" placeholder="Enter rate" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="amt">Amount(Qty)</label>
                                <div class="form-group">
                                    <input type="number" id="amt" name="amount" step="0.001" min="0.001" class="form-control" placeholder="Enter amount" required>
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
</div> --}}



<div class="d-none " id="addmodal" role="document">
    <h4 class="d-flex justify-content-between align-items-center">
        <span>
            Create Customer
        </span>
        <span class="btn btn-danger" style="font-size: 17px;" onclick="hideAdd()">
            &times;
        </span>
    </h4>
    <form id="addCustomer" method="POST" onsubmit="return saveData(event);">
        <div class="py-2">
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <label for="name">Customer Name</label>
                    <div class="form-group">
                        <input type="text" id="name" name="name" class="form-control next" data-next="phone"
                            placeholder="Enter customer name" required>
                    </div>
                </div>

                <div class="col-lg-6">
                    <label for="name">Customer Phone</label>
                    <div class="form-group">
                        <input type="number" id="phone" name="phone" class="form-control next"
                            data-next="address" placeholder="Enter customer phone" required>
                    </div>
                </div>

                <div class="col-lg-12">
                    <label for="name">Customer Address</label>
                    <div class="form-group">
                        <input type="text" id="address" name="address" class="form-control " data-next="panvat"
                            placeholder="Enter customer address" required>
                    </div>
                </div>
                <div class="col-lg-6">
                    <label for="name">Customer PAN/VAT</label>
                    <div class="form-group">
                        <input type="text" id="panvat" name="panvat" class="form-control " data-next="date"
                            placeholder="Enter customer PAN/VAT">
                    </div>
                </div>
                @if (auth_has_per('08.03'))
                    
                    <div class="col-12"></div>
                    <div class="col-lg-4">
                        <label for="date">Opening Balance Date</label>
                        <div class="form-group">
                            <input type="text" id="date" name="date" min="0" step="0.01"
                                class="form-control next calender" data-next="amount" placeholder="Enter Current Balance"
                                required>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <label for="amount">Opening Balance</label>
                        <div class="form-group">
                            <input type="number" id="amount" name="amount" min="0" step="0.01"
                                class="form-control next" data-next="address" placeholder="Enter Current Balance" required>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <label for="amounttype">Balance Type</label>
                        <div class="form-group">
                            <select name="amounttype" id="amounttype" class="form-control show-tick ms">
                                <option value="1">CR</option>
                                <option value="2">DR</option>
                            </select>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-raised btn-primary waves-effect" type="submit">Submit Data</button>
            <span type="button" class="btn btn-danger waves-effect" onclick="hideAdd()">Cancel</span>
        </div>
    </form>
    <hr>
</div>

@section('js1')
    {{-- <script>
        function saveData(e) {
            e.preventDefault();
            if (!lock) {
                lock = true;
                showProgress('Adding Customer');
                var data = new FormData(document.getElementById('addCustomer'));
                axios.post('{{ route('admin.customer.add') }}', data)
                    .then((res) => {
                        $('#data').append(res.data);
                        hideProgress();
                        lock = false;
                        $('#addModal').modal('hide');
                        document.getElementById('addCustomer').reset();
                        showNotification('bg-success', 'Customer Added Sucessfully');

                    })
                    .catch((err) => {
                        showNotification('bg-danger', 'Customer cannot be added,Please try again.');
                        hideProgress();
                        lock = false;
                    })
            }
        }
    </script> --}}
    <script>
        function showAdd() {
            $('#addmodal').removeClass('d-none')
        }

        function hideAdd() {
            $('#addmodal').addClass('d-none')

        }

        function saveData(e) {
            e.preventDefault();
            if (!lock) {
                lock = true;
                showProgress('Adding Customer');
                var data = new FormData(document.getElementById('addCustomer'));
                axios.post('{{ route('admin.customer.add') }}', data)
                    .then((res) => {
                        $('#data').append(res.data);
                        hideProgress();
                        lock = false;
                        hideAdd();
                        document.getElementById('addCustomer').reset();
                        showNotification('bg-success', 'Customer Added Sucessfully');

                    })
                    .catch((err) => {
                        showNotification('bg-danger', 'Customer cannot be added,Please try again.');
                        hideProgress();
                        lock = false;
                    })
            }
        }
    </script>
@endsection
