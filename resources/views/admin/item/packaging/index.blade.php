@extends('admin.layouts.app')
@section('title', 'Items - Packing')
@section('head-title')
<a href="{{route('admin.item.index')}}">Items</a> / Packaging
@endsection

@section('toobar')
<a href="{{route('admin.item.packaging.add')}}" class="btn btn-primary">New Repacking</a>
@endsection
@section('content')
    <table class="table table-bordered">
        <thead>

            <tr>
                <th>
                    #ID
                </th>
                <th>
                    Date
                </th>
                <th>
                    Items Repackaged
                </th>
                <th>

                </th>
            </tr>
        </thead>
        <tbody id="datas">

        </tbody>
    </table>
@endsection
@section('js')
    <script>
        const repackages={!! json_encode($repackages) !!};
        const repackageItems={!! json_encode($repackageItems) !!};
        const url = '{{route('admin.item.packaging.view',['id'=>'xxx_id'])}}';
        $(document).ready(function () {
            let html='';
            repackages.forEach(repackage => {
                repackageItem=repackageItems.find(o=>o.repackage_id==repackage.id);
                count=0;
                if(repackageItem!=undefined){
                   count=repackageItem.itemCount;
                }

                html+=`<tr>
                    <td class="${repackage.canceled==1?'canceled':''}">${repackage.id}
                        ${repackage.canceled==1?'<span class="badge badge-danger">Canceled</span>':''}
                        </td>
                    <td>${toNepaliDate(repackage.date)}</td>
                    <td>${count} Items</td>
                    <td><a href="${url.replace('xxx_id',repackage.id)}">View Detail</a></td>
                    </tr>
                `;
            });
            $('#datas').html(html);

        });
    </script>
@endsection
