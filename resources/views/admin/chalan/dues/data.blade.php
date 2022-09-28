<table class="table">
    <tr>
        <th>
            Date
        </th>
        <th>
            Customer
        </th>
        <th>
            Phone
        </th>
        <th>
            Due
        </th>
        <th>
            Paid
        </th>
        <th>
            Remaning Amount
        </th>

    </tr>
    @foreach ($dues as $due)
        <tr>
            <td>
                {{_nepalidate($due->date)}}
            </td>
            <td>
                {{$due->name}}
            </td>
            <td>
                {{$due->phone}}
            </td>
            <td>
                {{$due->amount}}
            </td>
            <td>
                {{$due->paid}}
            </td>
            <td>
                {{$due->amount-$due->paid}}
            </td>

        </tr>


    @endforeach
    <tr>
        <th colspan="3">
            Total
        </th>
        <th>
            {{$dues->sum('dues')}}
        </th>
        <th>
            {{$dues->sum('paid')}}
        </th>
        <th>
            {{$dues->sum('dues') - $dues->sum('paid')}}
        </th>
    </tr>
</table>