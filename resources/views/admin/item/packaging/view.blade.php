@extends('admin.layouts.app')
@section('title', 'Items - Packing')
@section('head-title')
<a href="{{route('admin.item.index')}}">Items</a> /
<a href="{{route('admin.item.packaging.index')}}">Packaging</a> /
 #{{$repackage->id}}
@endsection

@section('toobar')
@endsection
@section('content')
    <div class="row">
        <div class="col-md-3">
            <strong>Ref ID</strong> <br>
            #{{$repackage->id}}
        </div>
        <div class="col-md-3">
            <strong>Date</strong> <br>
            {{_nepalidate($repackage->date)}}
        </div>
        @if (env('multi_stock',false) )
        <div class="col-md-3">
            <strong>Center</strong> <br>
            {{\App\Models\Center::where('id',$repackage->center_id)->first(['name'])->name}}
        </div>
        @endif
        <div class="col-md-3 pt-3">
            <a href="{{route('admin.item.packaging.cancel',['id'=>$repackage->id])}}" class="btn btn-danger" onclick="return prompt('Enter yes to continue')=='yes';">Cancel</a>
        </div>
    </div>
    <hr>
    <table class="table table-bordered">
        <thead>

            <tr>
                <th>
                    #ID
                </th>
                <th>
                    From Item
                </th>
                <th>
                    From Amount
                </th>
                <th>
                    To  Item
                </th>
                <th>
                    To Amount
                </th>

            </tr>
        </thead>
        <tbody id="datas">

        </tbody>
    </table>
@endsection
@section('js')
    <script>
        const repackage={!! json_encode($repackage) !!};
        const repackageItems={!! json_encode($repackageItems) !!};
        $(document).ready(function () {
            let html='';
            repackageItems.forEach(repackageItem => {
                html+=`<tr>
                    <td>${repackageItem.id}</td>
                    <td>${repackageItem.from_item}</td>
                    <td>${repackageItem.from_amount}</td>
                    <td>${repackageItem.to_item}</td>
                    <td>${repackageItem.to_amount}</td>
                    </tr>
                `;
            });
            $('#datas').html(html);

        });
    </script>
@endsection
