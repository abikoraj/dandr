<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" data-ff="name">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Create Employee</h4>
            </div>
            <hr>
            <div class="card">
                <div class="body">
                    <form id="form_validation" method="POST" onsubmit="return saveData(event);">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="name">Employee Name</label>
                                <div class="form-group">
                                    <input type="text" id="name" name="name" class="form-control next" data-next="phone" placeholder="Enter employee name" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="name">Employee Phone</label>
                                <div class="form-group">
                                    <input type="number" id="phone" name="phone" class="form-control next" data-next="address" placeholder="Enter employee phone" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="name">Employee Address</label>
                                <div class="form-group">
                                    <input type="text" id="address" name="address" class="form-control next" data-next="salary" placeholder="Enter employee address" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="name">Employee Salary</label>
                                <div class="form-group">
                                    <input type="number" id="salary" name="salary" class="form-control next" data-next="start" placeholder="Enter employee salary" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="name">Start Date</label>
                                <div class="form-group">
                                    <input type="text" id="start" name="start" class="form-control calender next" data-next="end" placeholder="Enter employment start date" >
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="name">End Date</label>
                                <div class="form-group">
                                    <input type="text" id="end" name="end" class="form-control calender next" data-next="acc" placeholder="Enter employment end date" >
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label for="name">Bank/Account Number</label>
                                <div class="form-group">
                                    <input type="text" id="acc" name="acc" class="form-control" placeholder="Enter Bank Detail" >
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
