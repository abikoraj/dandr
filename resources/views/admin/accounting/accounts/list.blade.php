<div class="shadow p-2 mb-3">
    <h5 class="d-flex justify-content-between align-items-center">
        <span>Assets</span>
        <span>
            <button class="btn btn-sm btn-primary" onclick="addAccount(1)">Add New Asset Account</button>
        </span>
    </h5>
    <table class="table">
        <thead>
            <tr>
                <th>Acc ID</th>
                <th>Account</th>
                <th>Balance</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accounts[1] as $account)
                <tr>
                    <td>{{$account->identifire}}</td>
                    <td>
                        {{$account->name}}
                    </td>
                    <td>
                        @if ($account->identifire=="1.2")
                        {{$account->bankbalance()}}

                        @else
                        {{$account->amount}}
                        @endif

                    </td>
                    <td>
                        @if ($account->identifire=="1.1")

                        @elseif ($account->identifire=="1.2")
                            <a href="{{route('admin.bank.index',['account'=>$account->id])}}" class="btn btn-success">Manage Bank Accounts</a>
                        @else
                            <a href="{{route('admin.accounting.subaccounts',['id'=>$account->id])}}" target="blank" class="btn btn-success">Manage Sub Accounts</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="shadow p-2 mb-3">
    <h5>Libilities</h5>
    <table class="table">
        <thead>
            <tr>
                <th>Acc ID</th>
                <th>Account</th>
                <th>Balance</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($accounts[2] as $account)
                <tr>
                    <td>{{$account->identifire}}</td>
                    <td>
                        {{$account->name}}
                    </td>
                    <td>
                        {{$account->amount}}
                    </td>
                    <td>

                        <a href="{{route('admin.accounting.subaccounts',['id'=>$account->id])}}" target="blank" class="btn btn-success">Manage Sub Accounts</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
