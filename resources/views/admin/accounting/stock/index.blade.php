@extends('admin.layouts.app')
@section('title','accounting - stock')
@section('content')
@include('admin.accounting.stock.add')

<div class="shadow mb-3">
    <div class="p-3">
        <form action="{{route('admin.accounting.stock.index')}}"  id="loadData">
            @csrf

            @include('admin.layouts.daterange',['alltext'=>'--','reporttitle'=>'Duration'])
            <hr>
            <div>
                <button class="btn btn-primary">Load Data</button>
            </div>
        </form>
        <div class="mt-3">

            <table class="table table-bordered ">
                <thead>
                    <tr>
                        <th>
                            Date
                        </th>
                        <th>
                            Opening
                        </th>
                        <th>
                            Closing
                        </th>
                        <th>

                        </th>
                    </tr>

                </thead>
                <tbody id="stockData">

                </tbody>

            </table>
        </div>
    </div>

</div>
@endsection
@section('js')
    <script>
        var stocks=[];
        $(document).ready(function () {
            $('#loadData').submit(function (e) {
                e.preventDefault();
                console.log($('#type').val());
                if($('#type').val()==-1){
                    alert('Please select a duration');
                    return;
                }

                showProgress("Loading Data");
                axios.post(this.action,new FormData(this))
                .then((res)=>{
                    console.log(res.data);
                    html=res.data.map(o=>{
                        return `<tr id="stock-${o.id}">
                            <td>${toNepaliDate(o.date)}</td>
                            <td>${o.opening==null?'--':o.opening}</td>
                            <td>${o.closing==null?'--':o.closing}</td>
                            <td>
                                <button class="btn btn-danger" onclick="del(${o.id})">Del</button>
                            </td>
                            </tr>`
                    });
                    $('#stockData').html(html);
                    hideProgress();
                })
                .catch((err)=>{
                    console.log(err);
                    hideProgress();
                })
            });
        });

        function del(id){
            if(prompt('Enter yes to continue')=='yes'){
                showProgress('Deleting');
                axios.post('{{route('admin.accounting.stock.del')}}',{id:id})
                .then((res)=>{
                    $('#stock-'+id).remove();
                    hideProgress();
                })
                .catch((err)=>{
                    hideProgress();
                })
            }
        }
    </script>

@endsection
