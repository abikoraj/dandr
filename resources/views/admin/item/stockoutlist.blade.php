@extends('admin.layouts.app')
@section('title', 'Items - Stock Outs')
@section('head-title')
    <a href="{{ route('admin.item.index') }}">Items</a> / Stock Outs
@endsection
@section('toobar')
<a href="{{route('admin.item.stockout')}}" class="btn btn-primary">New Stock Out</a>
@endsection
@section('content')

    <table class="table table-bordered">
        <tr>
            <th>
                REF ID
            </th>
            <th>
                Date
            </th>
            <th>
                From Branch
            </th>
            <th>
                To Branch
            </th>
            <th>
                Status
            </th>
            <th>

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
                xxx_date
            </xxx_td>
            <xxx_td>
                xxx_from_center
            </xxx_td>
            <xxx_td>
                xxx_center
            </xxx_td>
            <xxx_td>
                xxx_status
            </xxx_td>
            <xxx_td>
                <a href="{{route('admin.item.stockout-view',['id'=>'xxx_id'])}}" class="btn btn-success">View</a>
                <a href="{{route('admin.item.stockout-print',['id'=>'xxx_id'])}}" class="btn btn-primary">Print</a>
            </xxx_td>
        </xxx_tr>
    </span>
@endsection
@section('js')
    <script>
        const centers={!! json_encode($centers) !!};
        const items={!!json_encode($stockOuts)!!};
        window.onload=()=>{
            const template=$('#item-template').html();
            let html='';
            items.forEach(item => {
                from=centers.find(o=>o.id==item.from_center_id);
                to=centers.find(o=>o.id==item.center_id);
                let ts=template.replaceAll('xxx_tr','tr');
                ts=ts.replaceAll('xxx_td','td');
                ts=ts.replaceAll('xxx_id',item.id);
                ts=ts.replaceAll('xxx_from_center',from.name);
                ts=ts.replaceAll('xxx_center',to.name);
                ts=ts.replaceAll('xxx_status',item.canceled==1?"Canceled":"");
                ts=ts.replaceAll('xxx_date',toNepaliDate(item.date));
                html+=ts;
            });
            $('#data').html(html);


        };

    </script>
@endsection
