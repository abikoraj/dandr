<table class="table table-borered">
    <tr>
        <td>Bill No</td>
        <td>Date</td>
        <td>Customer</td>
        <td style="max-width: 25%;">Items</td>
        <td>Discount</td>
        <td>Net Total</td>
        <td>Paid</td>
        <td>Due</td>
    </tr>
    <tbody >
        @foreach ($bills as $bill)
            <tr>
                <td>{{$bill->id}}</td>
                <td>{{_nepalidate($bill->date)}}</td>
                <td>{{$bill->name}}</td>
                <td>{{$bill->billitems}}</td>
                <td>{{(float)$bill->dis}}</td>
                <td>{{(float)$bill->net_total}}</td>
                <td>{{(float)$bill->paid}}</td>
                <td>{{(float)$bill->due}}</td>
            </tr>
        @endforeach
    </tbody>
</table>