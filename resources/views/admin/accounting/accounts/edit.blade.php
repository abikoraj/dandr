
<div class="container">
    @if (in_array($account->identifire,$nameEdit) && in_array($account->identifire,$calculated))
    <h5 class="text-center">
        This account can only managed via subaccounts.
    </h5>
    @else
        <form action="{{route('admin.accounting.accounts.edit',['id'=>$account->id])}}" method="post" onsubmit="return updateAccount(event,this);">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <label for="name">Name</label>
                    @if (in_array($account->identifire,$nameEdit))
                    <div class="form-control">
                        {{$account->name}}
                    </div>
                    @else
                    <input type="text" name="name" id="ename" class="form-control" required value="{{$account->name}}">
                    @endif
                </div>
                <div class="col-md-4">
                    <label for="amount">Amount</label>
                    @if (in_array($account->identifire,$calculated))
                    <div class="form-control">
                        {{$account->amount}}
                    </div>
                    @else
                    <input type="number" min="0" name="amount" id="eamount" class="form-control" required value="{{$account->amount}}">
                    @endif
                </div>
                <div class="col-md-4 pt-4">
                    <button class="btn btn-primary">
                        Update Account
                    </button>
                </div>
            </div>
        </form>
    @endif
</div>
