@extends('admin.layouts.app')
@section('title', 'Items - Stock Outs')
@section('head-title')
    <a href="{{ route('admin.item.index') }}">Items</a> /
    <a href="{{ route('admin.item.stockout-list') }}">Stock Outs</a> / #{{$id}}
@endsection
@section('content')

    <div class="row">
        <div class="col-md-4">
            <div class="font-weight-bold">
                Date
            </div>
            <div>
                {{_nepalidate($stockOut->date)}}
            </div>
        </div>
        <div class="col-md-4">
            <div class="font-weight-bold">
                Center
            </div>
            <div>
                {{$stockOut->name}}
            </div>
        </div>
        <div class="col-md-4 py-2" onclick="cancelStockOut()">
            @if ($stockOut->canceled==0)
                <button class="btn btn-danger">
                    Cancel Stock Out
                </button>
            @endif
        </div>
    </div>
    <table class="table table-bordered">
        <tr>
            <th>
                REF ID
            </th>
            <th>
                Item
            </th>
            <th>
                Quantity
            </th>


        </tr>
        <tbody id="data"></tbody>
    </table>
    <span class="d-none" id="item-template">
        <xxx_tr>
            <xxx_td>
                #xxx_id
            </xxx_td>
            <xxx_td>
                xxx_name
            </xxx_td>
            <xxx_td>
                xxx_qty
            </xxx_td>

        </xxx_tr>
    </span>
@endsection
@section('js')
    <script>
        const items={!!json_encode($stockOutItems)!!};
        window.onload=()=>{
            const template=$('#item-template').html();
            let html='';
            items.forEach(item => {

                let ts=template.replaceAll('xxx_tr','tr');
                ts=ts.replaceAll('xxx_td','td');
                ts=ts.replaceAll('xxx_id',item.id);
                ts=ts.replaceAll('xxx_name',item.title);
                ts=ts.replaceAll('xxx_qty',item.amount);
                html+=ts;
            });
            $('#data').html(html);


        };

        function cancelStockOut(){
            if(prompt("Type yes to cancel stock")=='yes'){
                axios.get('{{route('admin.item.stockout-cancel',['id'=>$id])}}')
                .then((res)=>{
                    window.location.replace('{{ route('admin.item.stockout-list') }}');
                })
                .catch((err)=>{
                    showNotification('bg-danger', 'Some Error Occured Please Try Again');
                })
            }
        }
    </script>
@endsection
