<div id="distributer-data" class="card shadow p-2">
    <h4 class="title d-flex justify-content-between"> 
        <span>Distributer Detail</span>
        <span>
            <span class="btn btn-primary btn-sm toogle ml-2" data-on="true" data-collapse="#distributer-detail" >
                <span class="on">Hide</span>
                <span class="off">show</span>
            </span>
        </span>
    </h4>
    <hr class="mt-0 mb-1">
    <div class="row" id="distributer-detail">
        <div class="col-md-3">
            <label for="credit_limit">
                Credit Limit (Rs)
            </label>
            <input type="text" value="0" name="credit_limit" id="credit_limit" class="form-control next" data-next="credit_days">
        </div>
        <div class="col-md-3">
            <label for="credit_days">
                Credit Limit (Days)
            </label>
            <input type="text" value="155" name="credit_days" id="credit_days" class="form-control next" data-next="snf_rate">
        </div>
        <div class="col-md-3">
            <label for="snf_rate">
                SNF Rate
            </label>
            <input type="text" name="snf_rate" id="snf_rate" class="form-control next" data-next="fat_rate">
        </div>
        <div class="col-md-3">
            <label for="fat_rate">
                Fat Rate
            </label>
            <input type="text" name="fat_rate" id="fat_rate" class="form-control next" data-next="added_rate">
        </div>
        <div class="col-md-3">
            <label for="added_rate">
                Added Rate (per ℓ)
            </label>
            <input type="text" name="added_rate" id="added_rate" class="form-control next" data-next="fat_rate">
        </div>
        <div class="col-md-3 ">
            <label for="f_rate">
                <input type="checkbox" name="d_is_fixed" class="mr-2 switch" value="1" class="" data-switch="#d_fixed_rate">Fixed Rate
            </label>
            <input type="number" step="0.01" min="0" placeholder="Milk Rate" name="d_fixed_rate" id="d_fixed_rate" class="form-control">
        </div>
    </div>
</div>