<!-- add modal -->

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" data-ff="iname">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel">Create New Item</h4>
            </div>
            <hr>
            <form id="add-bill" onsubmit="return saveData(event);">
                <div class="card mb-0">
                    <div class="body" id="add-step1">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <label for="name">Item Name</label>
                                <div class="form-group">
                                    <input type="text" id="iname" name="name" class="form-control next" data-next="inum"
                                        placeholder="Enter item name" required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <label for="name">Item Number / Barcode</label>
                                <div class="form-group">
                                    <input type="text" id="inum" name="number" class="form-control next"
                                        data-next="cprice" placeholder="Enter unique item number" required>
                                </div>
                            </div>

                            @php
                                $w = env('use_wholesale', false);
                                $r = $w ? 4 : 6;
                            @endphp
                            @if (!env('multi_stock', false))
                                <div class="col-lg-{{ $r }}">
                                    <label for="cprice">{{ $w ? 'Cost' : 'Sale' }} Price</label>
                                    <div class="form-group">
                                        <input type="number" step="0.01" id="cprice" name="cost_price" min="0"
                                            class="form-control next" data-next="{{ $w ? 'wprice' : 'sprice' }}"
                                            placeholder="Enter {{ $w ? 'cost' : 'sale' }} price" required>
                                    </div>
                                </div>
                                @if ($w)
                                    <div class="col-lg-{{ $r }}">
                                        <label for="wprice">WholeSale Price</label>
                                        <div class="form-group">
                                            <input type="number" step="0.01" id="wprice" name="wholesale" min="0"
                                                class="form-control next" data-next="sprice"
                                                placeholder="Enter wholesale price" required>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-{{ $r }}">
                                    <label for="sprice">Sell Price</label>
                                    <div class="form-group">
                                        <input type="number" step="0.01" id="sprice" name="sell_price" min="0"
                                            class="form-control next" data-next="stock" placeholder="Enter sell price"
                                            required>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <label for="stock">Stock</label>
                                    <div class="form-group">
                                        <input type="number" step="0.01" id="stock" name="stock" min="0"
                                            class="form-control next" data-next="unit" placeholder="Enter stock"
                                            required>
                                    </div>
                                </div>
                            @endif
                            @if (!env('multi_package', false))
                                <div class="col-lg-4">
                                    <label for="unit">Unit Type</label>
                                    <div class="form-group">
                                        <input type="text" id="unit" name="unit" class="form-control next"
                                            data-next="reward" placeholder="Enter unit type" required>
                                    </div>
                                </div>
                            @else

                                <div class="col-lg-4">
                                    <label for="unit">Unit Type</label>
                                    <div class="form-group">
                                        <select name="conversion_id" id="conversion_id" class="form-control ms">
                                            @foreach ($units->where('is_base', 1) as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                            @endif

                            <div class="col-lg-4">
                                <label for="unit">Reward (%)</label>
                                <div class="form-group">
                                    <input type="number" id="reward" name="reward" step="0.001" min="0" value="0"
                                        class="form-control" placeholder="Enter item reward percentage">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label for="unit">Point / Unit</label>
                                <div class="form-group">
                                    <input type="number" id="points" name="points" step="0.001" min="0" value="0"
                                        class="form-control" placeholder="Enter item Points">
                                </div>
                            </div>


                            @if (env('use_distributer', false))
                                <div class="col-lg-3">
                                    <label for="dis_number">Distributer Number</label>
                                    <div class="form-group">
                                        <input type="text" id="dis_number" name="dis_number" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label for="dis_price">Distributer Rate</label>
                                    <div class="form-group">
                                        <input type="number" id="dis_price" name="dis_price" step="0.001" min="0"
                                            value="0" class="form-control">
                                    </div>
                                </div>
                            @endif
                            @if (env('use_online', false))
                                <div class="col-lg-3">
                                    <label for="image">Image</label>
                                    <div class="form-group">
                                        <input type="file" id="image" name="image" accept="image/*"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <label for="description">Description</label>
                                    <div class="form-group">
                                        <textarea id="description" name="description" class="form-control"></textarea>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if (env('multi_stock', false))
                        <div class="p-3" id="multistock">

                            <table class="table">
                                <tr>
                                    <th>
                                        Branch
                                    </th>
                                    @if ($w)
                                        <th>
                                            Wholesale
                                        </th>
                                    @endif
                                    <th>
                                        Retail
                                    </th>
                                    <th>

                                        Stock
                                    </th>
                                    <th>

                                    </th>
                                </tr>
                                @foreach ($centers as $center)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="centers[]" value="{{ $center->id }}">
                                            {{ $center->name }}
                                        </td>
                                        @if ($w)
                                            <td>
                                                <input class="form-control center-wholesale pos_only_required"
                                                    id="center-wholesale-{{ $center->id }}" type="number"
                                                    name="wholesale_{{ $center->id }}" required>
                                            </td>
                                        @endif
                                        <td>
                                            <input class="form-control center-rate pos_only_required" type="number"
                                                name="rate_{{ $center->id }}" required
                                                id="center-rate-{{ $center->id }}">
                                        </td>
                                        <td>
                                            <input class="form-control" type="number" name="qty_{{ $center->id }}"
                                                required value="0">
                                        </td>
                                        <td>
                                            <span class="btn btn-success" onclick="copyPrice({{ $center->id }})">
                                                Copy
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                    @endif
                    <hr>
                    <div class="row m-0">

                        <div class="col-lg-3">
                            <input type="checkbox" checked name="trackstock" id="trackstock" value="1">
                            <label for="trackstock">Track Stock</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="checkbox" checked name="trackexpiry" id="trackexpiry" value="1">
                            <label for="trackexpiry">Track Expiry</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="checkbox" name="sellonline" id="sellonline" value="1">
                            <label for="sellonline">Sell Online</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="checkbox" name="disonly" id="disonly" value="1">
                            <label for="disonly">Sell Distributor</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="checkbox" onchange="posOnlyChange(this)" name="posonly" id="posonly" value="1" checked>
                            <label for="posonly">Sell POS</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="checkbox" name="farmeronly" id="farmeronly" value="1">
                            <label for="farmeronly">Sell Farmer</label>
                        </div>
                        <div class="col-lg-3">
                            <input type="checkbox" name="taxable" id="taxable" value="1">
                            <label for="taxable">Taxable</label>
                        </div>
                        <div class="col-lg-12"></div>
                        <div class="col-lg-3">
                            <label for="tax">Tax/VAT</label>
                            <div class="form-group">
                                <input type="number" id="tax" name="tax" step="0.001" min="0" value="13"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <label for="expirydays">Expiary Days</label>
                            <div class="form-group">
                                <input type="number" id="expirydays" name="expirydays" step="0.001" min="0" value="0"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <label for="minqty">Min Online Qty</label>
                            <div class="form-group">
                                <input type="number" id="minqty" name="minqty" step="0.001" min="0" value="0"
                                    class="form-control">
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
        const multistock = {{ env('multi_stock', false) }};
        const multipackage = {{ env('multi_package', false) }};

        function posOnlyChange(ele){
            if(ele.checked){
                $('.pos_only_required').attr('required', 'required');

            }else{
                $('.pos_only_required').removeAttr('required');

            }
        }

        function posEditOnlyChange(ele){
            if(ele.checked){
                $('.pos_edit_only_required').attr('required', 'required');

            }else{
                $('.pos_edit_only_required').removeAttr('required');

            }
        }


        function copyPrice(id) {
            const wholesale = $('#center-wholesale-' + id).val();
            const rate = $('#center-rate-' + id).val();
            $('.center-rate').val(rate);
            $('.center-wholesale').val(wholesale);
        }

        function saveData(e) {
            e.preventDefault();
            if (!lock) {
                lock = true;
                var bodyFormData = new FormData(document.getElementById('add-bill'));
                axios({
                        method: 'post',
                        url: '{{ route('admin.item.save') }}',
                        data: bodyFormData,
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(function(response) {
                        console.log(response);
                        showNotification('bg-success', 'Item added successfully!');
                        $('#largeModal').modal('toggle');
                        $('#add-bill').trigger("reset")
                        $('#itemData').prepend(response.data);
                        lock = false;
                    })
                    .catch(function(response) {
                        showNotification('bg-danger', 'Item Number already exist!');
                        //handle error
                        console.log(response);
                        lock = false;

                    });
            }
        }
    </script>
@endsection
