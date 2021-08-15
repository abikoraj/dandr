<div class="row">
    <div class="col-md-6">

        <div class="card my-1 p-2 shadow">
            <h4 class="title d-flex justify-content-between"> 
                <span>Permanent Address</span>
                <span>
        
                    <span class="btn btn-primary btn-sm toogle ml-2" data-on="false" data-collapse="#p-addr" >
                        <span class="on">Hide</span>
                        <span class="off">show</span>
                    </span>
                </span>
            </h4>
            <hr class="mt-0 mb-2">
            <div class="row" id="p-addr">
                <div class="col-md-4">
                    <label for="country">Country</label>
                    <input type="text" list="datalist-country" id="country" name="country" size="50" value="Nepal"
                        autocomplete="off" class="form-control next" data-next="" />
                </div>
                <div class="col-md-8">
                    <label for="province">Province</label>
                    <input type="text" list="datalist-province" id="province" name="province" size="50" 
                    autocomplete="off" class="form-control next" data-next="district" />
                </div>
                <div class="col-md-4">
                    <label for="district">District</label>
                    <input type="text" list="datalist-district" id="district" name="district" size="50" 
                    autocomplete="off" class="form-control next" data-next="mun" />
                </div>
                <div class="col-md-8">
                    <label for="mun">Municipality</label>
                    <input type="text" list="datalist-mun" id="mun" name="mun" size="50" 
                    autocomplete="off" class="form-control next" data-next="ward" />
                </div>
                <div class="col-md-4">
                    <label for="ward">Ward No</label>
                    <input type="text"  id="ward" name="ward" size="50" 
                    autocomplete="off" class="form-control next" data-next="tole" />
                </div>
                <div class="col-md-4">
                    <label for="tole">Tole</label>
                    <input type="text"  id="tole" name="tole" size="50" 
                    autocomplete="off" class="form-control next" data-next="house_no" />
                </div>
                <div class="col-md-4">
                    <label for="house_no">House No</label>
                    <input type="text"  id="house_no" name="house_no" size="50" 
                    autocomplete="off" class="form-control next" data-next="tole" />
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card my-1 p-2 shadow">
            <h4 class="title d-flex justify-content-between"> 
                <span>Current Address</span>
                <span>
        
                    <span class="btn btn-primary btn-sm" onclick="copyPermanent()">Same As Permanent</span>
                    <span class="btn btn-primary btn-sm toogle ml-2" data-on="false" data-collapse="#c-addr" >
                        <span class="on">Hide</span>
                        <span class="off">show</span>
                    </span>
                </span>
            </h4>
            <hr class="mt-0 mb-2">
            <div class="row" id="c-addr">
                <div class="col-md-4">
                    <label for="c_country">Country</label>
                    <input type="text" list="datalist-country" id="c_country" name="c_country" size="50" value="Nepal"
                        autocomplete="off" class="form-control next" data-next="c_province" />
                </div>
                <div class="col-md-8">
                    <label for="c_province">Province</label>
                    <input type="text" list="datalist-province" id="c_province" name="c_province" size="50" 
                    autocomplete="off" class="form-control next" data-next="c_district" />
                </div>
                <div class="col-md-4">
                    <label for="c_district">District</label>
                    <input type="text" list="datalist-district" id="c_district" name="c_district" size="50" 
                    autocomplete="off" class="form-control next" data-next="c_mun" />
                </div>
                <div class="col-md-8">
                    <label for="c_mun">Municipality</label>
                    <input type="text" list="datalist-mun" id="c_mun" name="c_mun" size="50" 
                    autocomplete="off" class="form-control next" data-next="c_ward" />
                </div>
                <div class="col-md-4">
                    <label for="c_ward">Ward No</label>
                    <input type="text"  id="c_ward" name="c_ward" size="50" 
                    autocomplete="off" class="form-control next" data-next="c_tole" />
                </div>
                <div class="col-md-4">
                    <label for="c_tole">Tole</label>
                    <input type="text"  id="c_tole" name="c_tole" size="50" 
                    autocomplete="off" class="form-control next" data-next="c_house_no" />
                </div>
                <div class="col-md-4">
                    <label for="c_house_no">House No</label>
                    <input type="text"  id="c_house_no" name="c_house_no" size="50" 
                    autocomplete="off" class="form-control next" data-next="tole" />
                </div>
            </div>
        </div>
    </div>
</div>
@section('js1')
    <script>
        function copyPermanent(){
            copyInput(
                [
                    '#c_country',
                    '#country',
                    '#c_province',
                    '#province',
                    '#c_district',
                    '#district',
                    '#c_mun',
                    '#mun',
                    '#c_ward',
                    '#ward',
                    '#c_tole',
                    '#tole',
                    '#c_house_no',
                    '#house_no',
                ]);
        }
    </script>
@endsection