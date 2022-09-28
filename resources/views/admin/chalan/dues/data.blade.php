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

    @php
        $dueTotal=0;
        $paidTotal=0;
    @endphp
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
                @php
                    $dueTotal+=$due->amount;
                @endphp
            </td>
            <td>
                {{$due->paid}}
                @php
                    $paidTotal+=$due->paid;
                @endphp
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
            {{$dueTotal}}
        </th>
        <th>
            {{$paidTotal}}
        </th>
        <th>
            {{$dueTotal-$paidTotal}}
        </th>
    </tr>
</table>