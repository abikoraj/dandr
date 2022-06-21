@extends('admin.layouts.app')
@section('title', 'Wastage')
@section('css')
    <link rel="stylesheet" href="{{asset('backend/plugins/select2/select2.css')}}">
    <link rel="stylesheet" href="{{asset('backend/css/datatable.css')}}">
@endsection

@section('head-title')
Wastage
@endsection
@section('content')
@include('admin.wastage.add')
<div class="shadow mb-3">
    <div class="card-body">
        <form action="{{route('admin.wastage.index')}}" method="POST" id="loadDataForm">
            @csrf
            @include('admin.layouts.daterange')
            <div class="row mt-2">
                <div class="col-md-3">
                    <label for="center">Center</label>
                    <select name="center_id" id="center_id" class="form-control ms">
                            <option value="-1">All</option>
                        @foreach ($centers as $center)
                            <option value="{{$center->id}}">{{$center->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div>

                        <input type="checkbox" name="showManufacture" id="showManufacture" value="1"> Show Manufacture Wastage
                    </div>
                </div>
                <div class="col-md-3 pt-1 pt-md-4">
                    <button class="btn btn-success">Load Data</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="shadow">
    <div class="card-body">
        <table class="table table-bordered" id="data">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Center</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="datas">

            </tbody>
        </table>
    </div>
</div>
@endsection
@section('js')
        <script src="{{asset('backend/plugins/select2/select2.min.js')}}"></script>
        <script src="{{asset('backend/js/datatable.js')}}"></script>
        <script>
            const items={!!json_encode($items)!!};
            var table='';
            var processUrl="{{route('admin.manufacture.process.detail',['id'=>'xxx_id'])}}";
            var datas=[];
            $(document).ready(function () {
                console.log(items);
                const itemOptionDatas=`<option></option>`+ (items.map(o=>{
                    return `<option value="${o.id}">${o.title}</option>`;
                }).join(''));
                $('#item_id').html(itemOptionDatas);
                $('#item_id').select2();
                $('#loadDataForm').submit(function(e){
                    e.preventDefault();
                    showProgress("loading Wastages");

                    axios.post(this.action,new FormData(this))
                    .then((res)=>{
                        datas=res.data;
                        datas.sort((a,b)=>b.id-a.id);
                        $('#datas').html(
                            datas.map(o=>{
                                let html= `<tr id="wastage-${o.id}">
                                    <td>${toNepaliDate(o.date)}</td>
                                    <td>${o.title}</td>
                                    <td>${o.amount}</td>
                                    <td>${o.rate}</td>
                                    <td>${o.center}</td><td>`;
                                if(o.manufacture_process_id!=null){
                                    html+= `<a target='_blank' href='${processUrl.replace('xxx_id',o.manufacture_process_id)}'>Manufacutre process</a> `;
                                }else{

                                    html+=`<a class="text-danger" onclick="del(event,${o.id})">Del</a>`;
                                }
                                html+=`</td></tr>`;
                                return html;
                            }).join('')
                        );
                        hideProgress();
                    })
                    .catch((err)=>{
                        alert('Some error occured please try again');
                        hideProgress();

                    })
                });
            });

            function del(e,id){
                e.preventDefault();
                if(prompt('Enter yes to continue')=='yes'){
                    showProgress("Deleting Wastage");
                    axios.post('{{route('admin.wastage.del')}}',{id:id})
                    .then((res)=>{
                        showNotification('bg-success','Wastage deleted sucessfully');
                        $('#wastage-'+id).remove();
                        hideProgress();
                    })
                    .catch((err)=>{
                        showNotification('bg-danger','Wastge could not be deleted, please try again');
                        hideProgress();

                    })
                }
            }

        </script>
@endsection
