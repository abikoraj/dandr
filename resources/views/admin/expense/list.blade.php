@php
$total = 0;
@endphp
<h5  >
    {!!$title!!}
</h5>
<hr>
<div class="row">
    <div class="col-md-4 ">
        <input type="text" id="sid" placeholder="Search" class="form-control">

    </div>
</div>
<hr>
<table id="newstable1" class="table table-bordered table-striped table-hover js-basic-example dataTable">
    <thead>
        <tr>
            <th>Title</th>
            <th>Date</th>
            <th>Paid By</th>
            <th>Amount (Rs.)</th>
            <th>Payment Detail</th>
            <th>Remarks</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($exps as $exp)
            <tr id="expense-{{ $exp->id }}" data-name="{{ $exp->title }}" class="searchable">
                <td>{{ $exp->title }}</td>
                <td>{{ _nepalidate($exp->date) }}</td>
                <td>{{ $exp->payment_by }}</td>
                <td>{{ $exp->amount }}</td>
                <td>{{ $exp->payment_detail }}</td>
                <td>{{ $exp->remark }}</td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm"
                        onclick="initEdit('{{ $exp->title }}',{{ $exp->id }});">Edit</button>
                    |
                    <button class="btn btn-danger btn-sm" onclick="removeData({{ $exp->id }});">Delete</button>
                </td>
            </tr>
            @php
                $total += $exp->amount;
            @endphp
        @endforeach
        <tr>
            <td class="text-right" colspan="3"><strong>Total</strong></td>
            <td colspan="4"><strong>Rs.{{ $total }}</strong></td>
        </tr>
    </tbody>
</table>
