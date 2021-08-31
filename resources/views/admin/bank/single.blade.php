<div class="col-md-4 " id="bank-{{$bank->id}}">
    <form class="shadow mb-3 p-2" id="editBank-{{$bank->id}}" method="POST"  action="{{route('admin.bank.update')}}">
        @csrf
        <input type="hidden" name="id" value="{{$bank->id}}">
        <div class="row">
            <div class="col-lg-12">
                <label for="name">Name</label>
                <div class="form-group">
                    <input type="text" id="name" name="name" class="form-control next" data-next="phone" placeholder="Enter Bank Name" required value="{{$bank->name}}">
                </div>
            </div>
            <div class="col-lg-12">
                <label for="phone">Phone No</label>
                <div class="form-group">
                    <input type="text" id="phone" name="phone" class="form-control next" data-next="phone" placeholder="Enter Bank Phone No" required value="{{$bank->phone}}">
                </div>
            </div>    
            <div class="col-lg-12">
                <label for="address">Address</label>
                <div class="form-group">
                    <input type="text" id="address" name="address" class="form-control next" data-next="phone" placeholder="Enter Bank Address" required value="{{$bank->address}}">
                </div>
            </div> 
            <div class="col-lg-12">
                <label for="accno">Account No</label>
                <div class="form-group">
                    <input type="text" id="accno" name="accno" class="form-control next" data-next="phone" placeholder="Enter Bank Account No" required value="{{$bank->accno}}">
                </div>
            </div>       
            <div class="col-md-6">
                <button class="btn btn-primary w-100">Update</button>
            </div>
            <div class="col-md-6">
                <span class="btn btn-danger w-100" onclick="del({{$bank->id}})">Delete</span>
            </div>
        </div>
    </form>
</div>