<div class="card shadow p-1">
    {{-- <h4 class="title">Basic Info (f1)</h4>
    <hr> --}}
    <div class="row">
        {{-- <div class="col-md-3">
            <div class="image-input" id="image-holder">
                <label for="image">Image / Logo</label>
                <input type="file" name="image" id="image">
            </div>
        </div> --}}
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name" class="required">Name (f1) </label>
                        <input type="text" id="name" name="name" class="form-control next" data-next="name_nepali"
                            placeholder="Enter Name Here" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="name_nepali" class="required">Name (Devanagari)</label>
                        <input type="text" id="name_nepali" name="name_nepali" class="form-control next" data-next="phone"
                            placeholder="Enter Name Here" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" class="form-control next" data-next="address"
                            placeholder="Enter Phone Number Here">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="address">Street Address</label>
                        <input type="text" id="address" name="address" class="form-control "
                            placeholder="Enter Phone Number Here">
                    </div>
                </div>
            </div>
            <hr class="mt-0 mb-2">

            <div class="row">
                <div class="col-md-6">
                    <input type="radio" name="acc_type" id="acc_type_normal" class="switch" data-switch=".acc_type_normal" data-case=".acc_type_dependent"  value="normal" checked><label class="pl-2"
                        for="acc_type_normal">Normal Account (alt + n)</label>
                </div>
                <div class="col-md-6">
                    <input type="radio" name="acc_type" id="acc_type_dependent" class="switch" data-switch=".acc_type_dependent" data-case=".acc_type_normal" value="dependent"><label class="pl-2"
                        for="acc_type_dependent">Dependent Account (alt + d)</label>
                </div>
                {{-- <div class="col-md-12 acc_type_dependent mb-2">
                    <hr class="m-1">
                    <label for="ref_acc">Referal Account</label>
                    <input list="datalist-member" type="text" name="ref_acc" id="ref_acc" class="form-control">
                </div> --}}

            </div>
            <hr class="mt-0 mb-2">

            <div class="row">
                <div class="col-md-6">
                    <input type="radio" name="type" id="type-person" class="switch" data-switch=".type-person" data-case='.type-org' value="person" checked><label class="pl-2"
                        for="type-person">Person</label>
                </div>
                <div class="col-md-6">
                    <input type="radio" name="type" id="type-org" class="switch" data-switch=".type-org" data-case=".type-person" value="org"><label class="pl-2"
                        for="type-org">Organization</label>
                </div>
            </div>
            {{-- <div class="row type-person">
                <div class="col-md-12">
                    <hr class="mt-0 mb-2">
                </div>
                <div class="col-md-3">
                    <input type="radio" name="gender" id="gender-male"  value="0" checked><label class="pl-2"
                    for="gender-male">Male</label>
                </div>
                <div class="col-md-3">
                    <input type="radio" name="gender" id="gender-female"  value="1" ><label class="pl-2"
                    for="gender-female">Female</label>
                </div>
                <div class="col-md-3">
                    <input type="radio" name="gender" id="gender-others"  value="2" ><label class="pl-2"
                    for="gender-others">Others</label>
                </div>
            </div> --}}
            
            <hr >

            <div class="row">
                <div class="col-md-3">
                    <input type="checkbox" name="is_farmer" id="is_farmer" class="switch" data-switch="#farmer-data" value="1">
                    <label for="is_farmer" class="pl-2">Farmer (alt + f)</label>
                </div>
                <div class="col-md-3">
                    <input type="checkbox" name="is_distributer" id="is_distributer" class="switch" data-switch="#distributer-data" value="1">
                    <label for="is_distributer" class="pl-2">Distributer (alt + i)</label>
                </div>
                <div class="col-md-3">
                    <input type="checkbox" name="is_supplier" id="is_supplier" value="1" >
                    <label for="is_supplier" class="pl-2">Supplier (alt + s)</label>
                </div>
                {{-- <div class="col-md-3">
                    <input type="checkbox" name="is_customer" id="is_customer" value="1" >
                    <label for="is_customer" class="pl-2">Customer</label>
                </div>  --}}
                <div class="col-md-3">
                    <input type="checkbox" name="is_emp" id="is_emp" class="switch" data-switch="#employee-data" value="1">
                    <label for="is_emp" class="pl-2">Employee (alt + e)</label>
                </div>

            </div>
            <hr>
            @include('sahakari.member.snippets.farmerdetail')
            @include('sahakari.member.snippets.distributer')
            @include('sahakari.member.snippets.employee')
            @include('sahakari.member.snippets.member')

        </div>
    </div>
</div>