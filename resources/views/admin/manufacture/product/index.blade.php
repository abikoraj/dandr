@extends('admin.layouts.app')
@section('title','Manufacture Items')
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />

@endsection
@section('head-title','Manufacture Items')
@section('toobar')
@endsection
@section('content')
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session()->get('message') }}
    </div>
@endif
<div class="shadow p-2 mb-3">

    <form action="{{ route('admin.manufacture.product.add') }}" method="POST" id="addManufacturedProduct" >
        @csrf
        <div class="row">
            <div class="col-md-3  d-flex align-items-end">
                <div class="w-100">

                    <label for="item_id">Item</label>
                    <select name="item_id" id="item_id" class="form-control ms" required>

                    </select>
                </div>
            </div>
            <div class="col-md-2  d-flex align-items-end">
                <div class="w-100">
                    <label for="expairy_days">Expiary Days</label>
                    <input type="number" class="form-control" name="expairy_days" id="expairy_days" required>
                </div>

            </div>
            <div class="col-md-4">
                <label >Expected Finish Time</label>
                <hr class="my-1 mx-0">
                <div class="row">
                    <div class="col-4">
                        <label for="day">Day</label>
                        <input type="number" name="day" id="day" class="form-control" required value="0">
                    </div>
                    <div class="col-4">
                        <label for="hour">Hour</label>
                        <input type="number" name="hour" id="hour" class="form-control" required value="0">
                    </div>
                    <div class="col-4">
                        <label for="minute">Minute</label>
                        <input type="number" name="minute" id="minute" class="form-control" required value="0">
                    </div>
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-end">
               <div class="w-100">
                   <button class="btn btn-primary w-100">Save</button>
               </div>
            </div>

        </div>
    </form>
</div>
<div class="shadow p-2">
    <div class="row">
        <div class="col-md-3">
            <strong>Product</strong>
        </div>
        <div class="col-md-2">
            <strong>
                Expairy Days
            </strong>
        </div>
        <div class="col-md-3">
            <strong>
                Expected Finish Time
            </strong>
        </div>
    </div>
    <hr>
    <div id="manufaturedProducts">

    </div>
</div>
@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script>

        var lockk=false;
        const items={!! json_encode($items) !!};
        const products={!! json_encode($products) !!};
        templateURL='{{route('admin.manufacture.product.template.index',['id'=>'xxx_id'])}}';
        console.log(items);

        $(document).ready(function () {
            let itemOptions='<option ></option>';
            items.forEach(item => {
                itemOptions+="<option value='"+item.id+"'>"+item.title+"</option>"
            });
            $('#item_id').html(itemOptions);

            $('#item_id').select2({
                placeholder: 'Select a Item'
            });

            $('#manufaturedProducts').html(products.map(o=>renderProduct(o)))


            $('#addManufacturedProduct').submit(function(e){
                e.preventDefault();
                console.log(this.action);
                showProgress("Adding Manufatured Product");
                axios.post(this.action,new FormData(this))
                .then((res)=>{
                    const data=res.data;
                    data.title=$( "#item_id option:selected" ).text();
                    $('#manufaturedProducts').append(renderProduct(data));
                    this.reset();
                    $('#item_id').val(null).trigger('change');
                    hideProgress();
                })
                .catch((err)=>{
                    hideProgress();
                });
            });
        });

        function deleteProduct(id,title){
            if(prompt('Enter yes to delete Product'+title)=='yes'){
                showProgress('Deleting '+title);
                axios.post('{{route('admin.manufacture.product.del')}}',{id:id})
                .then((res)=>{
                    $('#product_'+id).remove();
                    hideProgress();
                })
                .catch((err)=>{
                    showNotification('bg-danger',"Product "+title+" Cannot be deleted");
                    hideProgress();

                });
            }
        }


        function renderProduct(o){
            return '<div id="product_'+o.id+'"><div  class="row"><div class="col-md-3">'+
                    o.title +
                '</div>'+
                '<div class="col-md-2">'+o.expairy_days+'</div>'+
                '<div class="col-md-3">'+
                    (o.day>0? o.day.toString()+" Days, ":'')+
                    (o.hour>0? o.hour.toString()+" Hours, ":'') +
                    (((o.day>0||o.hour>0)&&o.minute==0)?'':( o.minute.toString()+" Minutes,"))
                    +
                '</div>'+
                '<div class="col-md-4"><a class="btn btn-success" href="'+templateURL.replace('xxx_id',o.id)+'">Template</a><button class="btn btn-danger btn-sm" onclick="deleteProduct('+o.id+',\''+o.title+'\')">Delete</button></div>'+
                '</div><hr class="my-1"/></div>';
        }

    </script>


@endsection
