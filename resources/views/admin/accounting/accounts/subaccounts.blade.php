@extends('admin.layouts.app')
@section('head-title')
    <a href="{{ route('admin.accounting.index') }}">Accounting</a>
    / <a href="{{ route('admin.accounting.accounts.index') }}">Accounts ({{ $account->fiscalyear->name }})</a>
    @foreach ($parents as $parent)
        / <a href="{{ route('admin.accounting.subaccounts', ['id', $parent->id]) }}">{{ $parent->name }}</a>
    @endforeach
    / {{ $account->name }}
@endsection
@section('toobar')
    <button class="btn btn-primary" onclick="addAccount()">
        Add New Account
    </button>
@endsection
@section('content')
    <table class="table">
        <thead>
            <tr>
                <th>Acc ID</th>
                <th>Account</th>
                <th>Balance</th>
                <th></th>
            </tr>
        </thead>
        <tbody >
            @foreach ($accounts as $account)
                <tr>
                    <td>{{ $account->identifire }}</td>
                    <td>
                        {{ $account->name }}
                    </td>
                    <td>
                        {{ $account->amount }}
                    </td>
                    <td>
                        <button class="btn btn-primary " onclick="initUpdate({{ $account->id }});"> Edit </button>
                        <a href="{{ route('admin.accounting.subaccounts', ['id' => $account->id]) }}" target="blank"
                            class="btn btn-success">Manage Sub Accounts</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
@section('js')
    <script>

        const addURL="{{route('admin.accounting.accounts.add',['parent_id'=>$account->id,'type'=>$account->type])}}";
        function addAccount(){
            win.showGet(`Add New Account`,addURL);
        }

        function saveAccount(e,ele) {
            e.preventDefault();
            showProgress('Adding Account');
            axios.post(ele.action,new FormData(ele))
            .then((res)=>{

                window.location.reload();
            })
            .catch((err)=>{
                hideProgress();
                showNotification("bg-danger","Error : "+err.response.data.message)
            })

        }
        function initUpdate(id){
            const updateURL="{{route('admin.accounting.accounts.edit',['id'=>'xxx_id'])}}";
            win.showGet('Update Account',updateURL.replace('xxx_id',id));
        }

        function updateAccount(e,ele){
            e.preventDefault();
            if(prompt("Enter yes to continue")=='yes'){
                showProgress("Updating account");
                axios.post(ele.action,new FormData(ele))
                .then((res)=>{
                    hideProgress();
                    loadAccounts();
                    win.hide();
                })
                .catch((err)=>{
                    hideProgress();
                    if(err.response){
                        showNotification("bg-danger","Error : "+err.response.data.message);
                    }
                });
            }
        }
    </script>

@endsection
