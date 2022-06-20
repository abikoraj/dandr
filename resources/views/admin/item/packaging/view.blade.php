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
    <div class="row mb-3">
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
    <div class="shadow mb-3">
        <h5 class="p-2 mb-0">
            Repackaged Items
        </h5>
        <div class="px-2 pb-2">
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
        </div>
    </div>
    @if (count($costs)>0)
        <div class="shadow mb-3">
            <h5 class="p-2 mb-0">
                Repackaging Costs
            </h5>
            <div class="px-2 pb-2">

                <table class="table table-bordered">
                    <tr>
                        <th>Title</th>
                        <th>Amount</th>
                    </tr>
                    @foreach ($costs as $cost)
                        <tr>
                            <td>{{$cost->title}}</td>
                            <td>{{$cost->amount}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endif
    @if (count($materials)>0)
    <div class="shadow">
        <h5 class="p-2 mb-0">
            Repackaging Material Used
        </h5>
        <div class="px-2 pb-2">
            <table class="table table-bordered">
                <tr>
                    <th>Title</th>
                    <th>Qty</th>
                </tr>
                @foreach ($materials as $material)
                    <tr>
                        <td>{{$material->title}}</td>
                        <td>{{$material->qty}}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endif
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
