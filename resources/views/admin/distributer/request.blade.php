@extends('admin.layouts.app')
@section('title','Distributers')
@section('head-title','Distributers Request')
@section('content')

<div class="table-responsive">
    <table id="newstable1" class="table table-bordered table-striped table-hover js-basic-example dataTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Distributor Name</th>
                <th>Item Name</th>
                <th>Qty (Kg/Ltr)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody >
            @foreach ($disReqs as $item)
                <tr>
                    <td>{{ _nepalidate($item->date) }}</td>
                    <td>{{ $item->user->name }}</td>
                    <td>{{ $item->item_name}}</td>
                    <td>{{ $item->amount}}</td>
                    <td>
                        @if ($item->status == 0)
                            <a href="{{ route('change.status',$item->id) }}" onclick="return confirm('Are you sure ?');" class="badge badge-primary">Pending</a>
                        @else
                            <span class="badge badge-success">Success</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endsection
@section('js')

@endsection
