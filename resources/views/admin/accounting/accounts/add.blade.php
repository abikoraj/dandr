<div class="container mt-3">
    <form action="{{route('admin.accounting.accounts.add',['parent_id'=>$parent_id,'type'=>$type])}}" method="post" id="addAccountForm" onsubmit="return saveAccount(event,this);">
        @csrf

        <div class="row">
            <div class="col-md-3">
                <label for="identifire">Account ID</label>
                <div class="d-flex align-items-center">
                    <div class=" text-right  pr-0">
                        @if ($parent_id!=0)
                        {{$parent->identifire}}.
                        @else
                        {{$type}}.
                        @endif

                    </div>
                    <div class=" pl-0" style="flex-grow:1;">
                        <input type="number" required min="1" name="identifire" id="identifire" class="form-control">
                    </div>
                </div>


            </div>
            <div class="col-md-3"><label for="name">Name</label><input type="text" name="name" required minlength="2" class="form-control" id="name"></div>
            {{-- <div class="col-md-3"><label for="amount">Balance</label><input type="number" required min="0" name="amount" class="form-control" id="amount"></div> --}}
            <div class="col-md-3 pt-4" >
                <button class="btn btn-primary">Save Account</button>
            </div>
        </div>
    </form>
</div>
