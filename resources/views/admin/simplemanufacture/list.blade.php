@extends('admin.layouts.app')
@section('title','Manufactured List')

@section('head-title','Manufactured List')
@section('toobar')
@endsection
@section('content')
<table class="table table-bordered">
    <tbody>
        <tr>
            <th>S.n</th>
            <th>Date</th>
            <th>Items</th>
            <th>Qty(Kg./Ltr.)</th>
            <th>Ingredients(Name/Unit)</th>
        </tr>
    </tbody>
    <tbody>
        @foreach ($manu as $k => $item)
            <tr>
                <td>{{ $k+1 }}</td>
                <td>{{ _nepalidate($item->date) }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->qty }}</td>
                <td>
                    @foreach ($item->items as $i)
                        <p>{{ $i->product->name }},{{ $i->req_qty}}(Kg./Ltr.) </p>
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
