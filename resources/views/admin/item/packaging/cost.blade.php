<div class="col-md-6">

    <div class="shadow h-100">
        <div class="card-body">
            <form id="addCostForm">
                <div class="row mb-3">
                    <div class="col-12 pb-2">
                        <label>
                            Cost Title
                        </label>
                        <input type="text" class="form-control next" data-next="cost_amount" id="cost_title"
                            name="cost_title " required>
                    </div>
                    <div class="col-md-6">
                        <label for="cost_amount">Amount</label>
                        <input type="number" name="cost_amount" id="cost_amount" class="form-control" step="0.0001"
                            required>
                    </div>
                    <div class="col-md-6 pt-4">
                        <button class="btn btn-primary" id="addCost">Add Packaging Cost</button>
                    </div>
                </div>
            </form>
            <table class="table table-bordered">
                <thead>

                    <tr>
                        <th>Title</th>
                        <th>
                            Amount
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="costs"></tbody>
            </table>
        </div>
    </div>
</div>
@section('js2')
    <script>
        function removeCost(id) {
            const index = costs.findIndex(o => o.identifire == id);
            if (index != -1) {
                costs.splice(index, 1);
            }
            renderCostHtml();
        }
        function renderCostHtml() {
            $('#costs').html(costs.map(o => `
        <tr>
            <td>${o.title}</td>
            <td>${o.amount}</td>
            <td><button onclick="removeCost(${o.identifire})" class="btn btn-danger">Del</button></td>
        </tr>
        `));
        }





        $('#addCostForm').submit(function(e) {
            e.preventDefault();
            const title = $('#cost_title').val();
            const amount = $('#cost_amount').val();
            if (title == '') {
                alert('Please select a pacakaging material');
                $('#cost_title').focus();

                return;
            }
            if (amount <= 0) {
                alert('Please enter amount');
                $('#cost_amount').focus();

                return;
            }

            costs.push({
                identifire: i++,
                title: title,
                amount: amount
            });
            $('#cost_title').val('');
            $('#cost_amount').val(0);
            $('#cost_title').focus();
            renderCostHtml();

        });
    </script>
@endsection
