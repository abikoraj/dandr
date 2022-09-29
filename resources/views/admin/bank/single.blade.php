<div class="col-md-12 " id="bank-{{$bank->id}}">
    <form class="shadow mb-3 p-2" id="editBank-{{$bank->id}}" method="POST"  action="{{route('admin.bank.update')}}" onsubmit="return updateBank(event,this)">
        @csrf
        <input type="hidden" name="id" value="{{$bank->id}}">
        <div class="row m-0">
            <div class="p-0 col-lg-3">
                <label for="name">Bank Name</label>
                <div class="form-group">
                    <input type="text" id="name" name="name" class="form-control next" data-next="phone" placeholder="Enter Bank Name" required value="{{$bank->name}}">
                </div>
            </div>
            <div class="p-0 col-lg-2">
                <label for="phone">Phone No</label>
                <div class="form-group">
                    <input type="text" id="phone" name="phone" class="form-control next" data-next="phone" placeholder="Enter Bank Phone No" required value="{{$bank->phone}}">
                </div>
            </div>
            <div class="p-0 col-lg-2">
                <label for="address">Address</label>
                <div class="form-group">
                    <input type="text" id="address" name="address" class="form-control next" data-next="phone" placeholder="Enter Bank Address" required value="{{$bank->address}}">
                </div>
            </div>
            <div class="p-0 col-lg-2">
                <label for="accno">Account No</label>
                <div class="form-group">
                    <input type="text" id="accno" name="accno" class="form-control next" data-next="phone" placeholder="Enter Bank Balance" required value="{{$bank->accno}}">
                </div>
            </div>
            <div class="p-0 col-lg-2">
                <label for="">Balance</label>
                <div class="form-group">
                    <input type="text" id="" name="" class="form-control next"  readonly required value="{{$bank->balance}}">
                </div>
            </div>
            {{-- <div class="p-0 col-lg-12">
                <label for="balance">Balance</label>
                <div class="form-group">
                    <input type="number" min="0" value="{{$bank->balance}}"  id="balance" name="balance" class="form-control next" data-next="phone" placeholder="Enter Bank Account No" required>
                </div>
            </div> --}}
            <div class="col-md-6 p-0">
                <button class="btn btn-primary">Update</button>
                <a href="{{route('admin.accounting.accounts.ledger',['id'=>$bank->account_id])}}" class="btn btn-success">Ledger</a>
                {{-- <span class="btn btn-danger w-100" onclick="del({{$bank->id}})">Delete</span> --}}
            </div>
            <div class="col-md-6">
            </div>
        </div>
    </form>
</div>
