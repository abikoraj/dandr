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
<table id="expenses" class="table table-bordered table-striped table-hover js-basic-example dataTable">
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
    <tbody id="expensesList">
        @foreach ($exps as $exp)
            <tr id="expense-{{ $exp->id }}" data-name="{{ $exp->title }}" class="searchable">
                <td>{{ $exp->title }}</td>
                <td>{{ _nepalidate($exp->date) }}</td>
                <td>{{ $exp->payment_by }}</td>
                <td>{{ $exp->amount }}</td>
                <td>{{ $exp->payment_detail }}</td>
                <td>{{ $exp->remark }}</td>
                <td>
                    @if (auth_has_per('06.07'))
                    <button type="button" class="btn btn-primary btn-sm"
                        onclick="initEdit('{{ $exp->title }}',{{ $exp->id }});">Edit</button>
                    @endif
                    |
                    @if (auth_has_per('06.08'))
                    <button class="btn btn-danger btn-sm" onclick="removeData({{ $exp->id }});">Delete</button>
                    @endif
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
