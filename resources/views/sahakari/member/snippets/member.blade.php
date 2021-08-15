<div class="">
    {{-- <h4 class="title d-flex justify-content-between"> 
        <span>general Info</span>
        <span>

            <span class="btn btn-primary btn-sm toogle ml-2" data-on="false" data-collapse="#general-info" >
                <span class="on">Hide</span>
                <span class="off">show</span>
            </span>
        </span>
    </h4>
    <hr class="mt-0 mb-2"> --}}
    <div class="row" id="general-info">
        <div class="col-md-2">
            <label for="member_no" class="required">Member No (f5)</label>
            <input type="text"   class="form-control next mask " data-dmask="00000" data-next="dob" id="member_no" name="member_no" required>
        </div>
        <div class="col-md-2 acc_type_dependent mb-2">
            <label for="ref_acc">Referal Account</label>
            <input list="datalist-member" type="text" name="ref_acc" id="ref_acc" class="form-control">
        </div>
        <div class="col-md-2 type-person">
            <label for="n_gender">Gender</label>
            <select id="n_gender" id="n_gender" class="form-control select2 ms next" data-next="n_relation">
                <option value="0">Male</option>
                <option value="1">Female</option>
                <option value="2">Others</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="dob" ><span class="type-person">Date Of Birth</span><span class="type-org">Registration Date</span></label>
            <input type="text" class="form-control next calender" data-next="join_date" id="dob" name="dob">
        </div>
        <div class="col-md-2">
            <label for="join_date" >Join Date</label>
            <input type="text" class="form-control next-switch calender" data-next="father_name" data-switch="pan_no" data-from="type-person" id="join_date" name="join_date">
        </div>
        {{-- generational detail --}}
        <div class="col-md-2 type-person">
            <label for="father_name">Father's Name</label>
            <input type="text" class="form-control next " data-next="mother_name" id="father_name" name="father_name">
            
        </div>
        <div class="col-md-2 type-person">
            <label for="mother_name">Mother's Name</label>
            <input type="text" class="form-control next " data-next="grandfather_name" id="mother_name" name="mother_name">
        </div>
        <div class="col-md-2 type-person">
            <label for="grandfather_name">Grandfather's Name</label>
            <input type="text" class="form-control next " data-next="spouse_name" id="grandfather_name" name="grandfather_name">
        </div>
        <div class="col-md-2 type-person">
            <label for="spouse_name">Spouse's Name</label>
            <input type="text" class="form-control next " data-next="grandfather_name" id="spouse_name" name="spouse_name">
        </div>
        {{-- organizational detail --}}
        <div class="col-md-2 type-org">
            <label for="pan_no">PAN/VAT No</label>
            <input type="text" class="form-control next" data-next="reg_no" id="pan_no" name="pan_no">
            
        </div><div class="col-md-2 type-org">
            <label for="reg_no">Registration No</label>
            <input type="text" class="form-control next" data-next="req_no" id="reg_no" name="reg_no">
            
        </div>
    </div>

</div>