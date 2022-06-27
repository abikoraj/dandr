@extends('admin.layouts.app')
@section('title', 'Extra Income')
@section('head-title')
    Extra Income
@endsection
@section('toobar')
    <a href="{{ route('admin.accounting.extra.income.add') }}" class="btn btn-primary">Add New Income</a>
    <a href="{{ route('admin.accounting.extra.income.category') }}" class="btn btn-primary">Manage Categories</a>
@endsection
@section('content')
    <div class="shadow mb-3">
        <div class="p-3">
            <form action="{{ route('admin.accounting.extra.income.index') }}" id="loadIncomeForm">
                @csrf

                @include('admin.layouts.daterange', ['alltext' => '--'])
                <hr>
                <div>
                    <button class="btn btn-primary">Load Data</button>
                </div>
            </form>
        </div>
    </div>
    <div class="shadow p-3">
        <table class="table table-bordered">
            <tr>
                <th>
                    Date
                </th>
                <th>
                    Title
                </th>

                <th>
                    Amount
                </th>
                <th>
                    Category
                </th>
                <th>
                    Received By
                </th>
                <th>

                </th>
            </tr>
            <tbody id="incomeData">

            </tbody>
        </table>
    </div>
@endsection
@section('js')
    <script>
        const updateURL='{{route('admin.accounting.extra.income.update',['id'=>'xxx_id'])}}';
        $(document).ready(function() {
            $('#loadIncomeForm').submit(function(e) {
                e.preventDefault();
                axios.post(this.action, new FormData(this))
                    .then((res) => {
                        console.log(res.data);
                        const datas = res.data.map(o => {
                            return `<tr id="income-${o.id}">
                                <td>${toNepaliDate(o.date)}</td>
                                <td>${o.title}</td>
                                <td>${o.amount}</td>
                                <td>${o.category}</td>
                                <td>${o.received_by}</td>
                                <td>
                                    <a href="${updateURL.replace('xxx_id',o.id)}" class="btn btn-success">Edit</a>
                                    <button class="btn btn-danger" onclick="del(${o.id})">Del</button>
                                </td>
                                </tr>`;
                        });
                        console.log(datas,'datas');
                        $('#incomeData').html(datas.join(''));

                    })
                    .catch((err) => {

                    })
            });
        });

        function del(id){
            if(prompt('Enter yes to continue')=='yes'){
                showProgress('Deleting');
                axios.post('{{route('admin.accounting.extra.income.del')}}',{id:id})
                .then((res)=>{
                    hideProgress();
                    showNotification('bg-success','Income deleted sucessfully');
                    $('#income-'+id).remove();
                })
                .catch((err)=>{
                    hideProgress();
                    showNotification('bg-danger','Some error occured, Plese try again.');
                })
            }
        }
    </script>

@endsection
