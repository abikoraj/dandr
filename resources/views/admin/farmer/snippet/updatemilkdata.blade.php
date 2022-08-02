<div class="modal fade" id="milk_change" tabindex="-1" role="dialog" aria-labelledby="milk_changemodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Milk Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="snf_title">

                </div>
                <hr>
                <div class="row">
                    <input type="hidden" id="update_milk_id">
                    <div class="col-md-6">
                        <label for="fat">Morning Milk</label>
                        <input type="number" name="update_morninf" id="update_morning" class="form-control"
                            step="0.01">
                    </div>

                    <div class="col-md-6">
                        <label for="snf">Evening Milk</label>
                        <input type="number" name="update_evening" id="update_evening" class="form-control"
                            step="0.01">
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveMilkUpdate();">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    milklock = false;

    function showMilkUpdateNew(id) {
        const txt = $('#milkdata-' + id).html();
        const milkdata = JSON.parse(txt);
        console.log(txt, milkdata);
        $('#update_morning').val(milkdata.m_amount);
        $('#update_evening').val(milkdata.e_amount);
        $('#update_milk_id').val(milkdata.id);
        $('#milk_change').modal('show');

    }

    function showMilkUpdate(ele) {
        milkdata = $(ele).data('milk');
        console.log(milkdata);
        $('#update_morning').val(milkdata.m_amount);
        $('#update_evening').val(milkdata.e_amount);
        $('#update_milk_id').val(milkdata.id);
        $('#milk_change').modal('show');
    }

    function delMilkDataNew(id) {

        if (confirm("Do You Want TO delete Milk Data??")) {
            // console.log(ele.dataset.)
            d = {
                id: id
            };
            axios.post('{{ route('admin.milk.delete') }}', d)
                .then(function(reponse) {
                    milkDeleted(d);

                })
                .catch(function(err) {
                    showNotification('bg-danger', 'You hove no authority!');
                });
        }

    }

    function delMilkData(ele) {

        if (confirm("Do You Want TO delete Milk Data??")) {
            // console.log(ele.dataset.)
            d = {
                id: $(ele).data('milk').id
            };
            axios.post('{{ route('admin.milk.delete') }}', d)
                .then(function(reponse) {
                    milkDeleted(d);

                })
                .catch(function(err) {
                    showNotification('bg-danger', 'You hove no authority!');
                });
        }

    }

    function saveMilkUpdate() {
        if (!milklock) {
            milklock = true;
            d = {
                morning: $('#update_morning').val(),
                evening: $('#update_evening').val(),
                id: $('#update_milk_id').val()
            }
            axios.post('{{ route('admin.milk.update') }}', d)
                .then(function(reponse) {
                    milklock = false;
                    $('#milk_change').modal('hide');
                    console.log(d);
                    milkUpdated(d);

                })
                .catch(function(err) {

                    milklock = false;
                    showNotification('bg-danger', 'You hove no authority!');

                })
        }
    }
</script>
