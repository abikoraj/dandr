@extends('admin.print.app')
@section("content")
<div class="row">
    <div class="col-12 text-center">
        <div class="fw-bolder">
            Stock Out
        </div>
    </div>
    <div class="col-6">
        <div class="fw-bolder">
            Date
        </div>
        <div>
            {{_nepalidate($stockOut->date)}}
        </div>
    </div>
    <div class="col-6 text-end">
        <div class="fw-bolder">
            Center
        </div>
        <div>
            {{$stockOut->name}}
        </div>
    </div>

</div>
<table class="table table-bordered">
    <tr>

        <th>
            Item
        </th>
        <th>
            Quantity
        </th>


    </tr>
    <tbody id="data">
        @foreach ($stockOutItems as $item)
            <tr>
                <td>
                    {{$item->title}}
                </td>
                <td>
                    {{$item->amount}}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
