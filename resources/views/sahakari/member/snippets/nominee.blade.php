<div class="card my-3 p-2 shadow">
    <h4 class="title d-flex justify-content-between">
        <span>Nomiee Detail</span>
        <span class="btn btn-primary btn-sm toogle" data-collapse="#nomiee-detail" data-on="false">
            <span class="on">Hide</span>
            <span class="off">Show</span>
        </span>
    </h4>
    <hr class="mt-0 mb-2">
    <div id="nomiee-detail">
        <div class="row">
            <div class="col-md-2">
                <label for="n_name">Name</label>
                <input type="text" id="n_name" id="n_name" class="form-control next" data-next="n_name_nepali">
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label for="n_name_nepali" class="required">Name (Devanagari)</label>
                    <input type="text" id="n_name_nepali" name="n_name_nepali" class="form-control next"
                        data-next="n_phone" >
                </div>
            </div>
            <div class="col-md-2">
                <label for="n_phone">Phone No</label>
                <input type="text" id="n_phone" id="n_phone" class="form-control next" data-next="n_gender">
            </div>
            <div class="col-md-2">
                <label for="n_gender">Gender</label>
                <select id="n_gender" id="n_gender" class="form-control select2 ms next" data-next="n_relation">
                    <option value="0">Male</option>
                    <option value="1">Female</option>
                    <option value="2">Others</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="n_relation">Relation</label>
                <input type="text" id="n_relation" id="n_relation" class="form-control next" data-next="n_dob">
            </div>
            <div class="col-md-2">
                <label for="n_dob">Date of Birth</label>
                <input type="text" id="n_dob" id="n_dob" class="form-control next calender" data-next="n_father_name">
            </div>
            <div class="col-md-2">
                <label for="n_father_name">Father's Name</label>
                <input type="text" id="n_father_name" id="n_father_name" class="form-control next"
                    data-next="n_mothers_name">
            </div>
            <div class="col-md-2">
                <label for="n_mother_name">Mother's Name</label>
                <input type="text" id="n_mother_name" id="n_mother_name" class="form-control next"
                    data-next="n_grandfather_name">
            </div>
            <div class="col-md-2">
                <label for="n_grandfather_name">Grandfather's Name</label>
                <input type="text" id="n_grandfather_name" id="n_grandfather_name" class="form-control next"
                    data-next="n_relation">
            </div>

            <div class="col-md-2">
                <label for="n_father_name">Spouse Name</label>
                <input type="text" id="n_father_name" id="n_father_name" class="form-control next"
                    data-next="n_relation">
            </div>
            <div class="col-md-2">
                <label for="n_document_name">Document Title</label>
                <input type="text" id="n_document_name" name="n_document_name" class="form-control next"
                    data-next="n_document_no">
            </div>
            <div class="col-md-2">
                <label for="n_document_no">Document Title</label>
                <input type="text" id="n_document_no" name="n_document_no" class="form-control next"
                    data-next="n_issued_date">
            </div>
            <div class="col-md-2">
                <label for="n_issued_date">issued Date</label>
                <input type="text" id="n_issued_date" name="n_issued_date" class="form-control next"
                    data-next="n_issued_from">
            </div>
            <div class="col-md-2">
                <label for="n_issued_from">issued From</label>
                <input type="text" id="n_issued_from" name="n_issued_from" class="form-control next" data-next="">
            </div>
            <div class="col-md-12">
                <hr>
                <div class="text-right">
                    <span class="btn btn-primary btn-sm" onclick="copyMemberAddress()">Copy Address from Member</span>
                </div>
                <hr>
            </div>
            <div class="col-md-2">
                <label for="n_country">Country</label>
                <input type="text" list="datalist-country" id="n_country" name="n_country" size="50" value="Nepal"
                    autocomplete="off" class="form-control next" data-next="n_province" />
            </div>
            <div class="col-md-3">
                <label for="n_province">Province</label>
                <input type="text" list="datalist-province" id="n_province" name="n_province" size="50"
                    autocomplete="off" class="form-control next" data-next="n_district" />
            </div>
            <div class="col-md-2">
                <label for="n_district">District</label>
                <input type="text" list="datalist-district" id="n_district" name="n_district" size="50"
                    autocomplete="off" class="form-control next" data-next="n_mun" />
            </div>
            <div class="col-md-3">
                <label for="n_mun">Municipality</label>
                <input type="text" list="datalist-mun" id="n_mun" name="n_mun" size="50" autocomplete="off"
                    class="form-control next" data-next="n_ward" />
            </div>
            <div class="col-md-2">
                <label for="n_ward">Ward No</label>
                <input type="text" id="n_ward" name="n_ward" size="50" autocomplete="off" class="form-control next"
                    data-next="n_tole" />
            </div>
            <div class="col-md-2">
                <label for="n_tole">Tole</label>
                <input type="text" id="n_tole" name="n_tole" size="50" autocomplete="off" class="form-control next"
                    data-next="n_house_no" />
            </div>
            <div class="col-md-2">
                <label for="n_house_no">House No</label>
                <input type="text" id="n_house_no" name="n_house_no" size="50" autocomplete="off"
                    class="form-control next" data-next="n_document_name" />
            </div>
           
            
        </div>
    </div>
</div>
@section('js3')
    <script>
        function copyMemberAddress() {
            copyInput(
                [
                    '#n_country',
                    '#country',
                    '#n_province',
                    '#province',
                    '#n_district',
                    '#district',
                    '#n_mun',
                    '#mun',
                    '#n_ward',
                    '#ward',
                    '#n_tole',
                    '#tole',
                    '#n_house_no',
                    '#house_no',
                ]);

        }
    </script>
@endsection
