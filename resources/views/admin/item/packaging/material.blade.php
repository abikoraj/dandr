<div class="col-md-6">

    <div class="shadow h-100">
        <div class="card-body">
            <form id="addMaterialForm">
                <div class="row mb-3">
                    <div class="col-12 pb-2">
                        <label>
                            Packaging Material
                        </label>
                        <select required name="material_id" id="material_id" class="select2 ms show-tick form-control">

                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="material_qty">Qty</label>
                        <input required type="number" name="material_qty" step="0.0001" id="material_qty"
                            class="form-control">
                    </div>
                    <div class="col-md-6 pt-4">
                        <button class="btn btn-primary" id="addMaterial">Add Packaging Material</button>
                    </div>
                </div>
            </form>
            <table class="table table-bordered">
                <thead>

                    <tr>
                        <th>Material Name</th>
                        <th>
                            QTY
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="materials">

                </tbody>

            </table>
        </div>
    </div>
</div>
@section('js1')
    <script>
        function removeMaterial(id) {
            const index = materials.findIndex(o => o.identifire == id);
            if (index != -1) {
                materials.splice(index, 1);
            }
            renderMaterialHtml();
        }
        function renderMaterialHtml() {
            $('#materials').html(materials.map(o => `
            <tr>
                <td>${o.item_name}</td>
                <td>${o.qty}</td>
                <td><button onclick="removeMaterial(${o.identifire})" class="btn btn-danger">Del</button></td>
            </tr>
            `));
        }

        $('#material_id').change(function(e) {
            $('#material_qty').focus();
        });

        $('#material_qty').keydown(function(e) {
            if (e.which == 13) {
                $('#addMaterial').click();
            }
        });

        $('#addMaterialForm').submit(function(e) {
            e.preventDefault();
            const material_id = $('#material_id').val();
            const qty = $('#material_qty').val();
            if (material_id == null) {
                alert('Please select a pacakaging material');
                return;
            }
            if (qty <= 0) {
                alert('Please enter quantity');
                $('#material_qty').focus();

                return;
            }
            materials.push({
                identifire: i++,
                item_id: material_id,
                item_name: items.find(o => o.id == material_id).title,
                qty: qty
            });
            $('#material_id').val(null).change();
            $('#material_qty').val(0);
            $('#material_id').select2('open');
            renderMaterialHtml();

        });
    </script>
@endsection
