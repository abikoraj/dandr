<style>
    td,th{
        border:1px solid black;
    }
    table{
        width:100%;
        border-collapse: collapse;
    }
    thead {display: table-header-group;}
    tfoot {display: table-header-group;}
</style>
<h2 style="text-align: center;margin-bottom:0px;font-weight:800;font-size:2rem;">
    {{env('APP_NAME','Dairy')}}
</h2>

<div style="display: flex;justify-content: space-between;font-weight:800;">
    <span>
        Year : {{$year}}

    </span>
    <span>
        Month : {{$month}}
    </span>
</div>
<form action="{{route('admin.report.emp.session')}}" method="POST">
    @csrf

    <table>
        <thead>
            <tr>
                <th>
                    SN
            </th>
            <th>
                Employee Name
            </th>
            <th>
                Prev Advance
            </th>
            <th>
                Prev Salary
            </th>
            @if (env('use_employeetax'))
                <th>Full Salary</th>
                <th>Tax Deduction ({{env('emp_tax',1)}}%)</th>
            @endif
            <th>
                Salary
            </th>
            <th>
                Salary Paid
            </th>
            <th>
                Advance
            </th>
            <th>
                Returned
            </th>

            <th>
                Remaning Salary
            </th>
            <th>
                Remaning Advance
            </th>
            <th>
                Bank Detail
            </th>
            <th>Signature</th>
        </tr>
    </thead>
    @php
        $i=0;
        $_prevbalance=0;
        $_prevSalary=0;
        $_advance=0;
        $_salary=0;
        $_paid=0;
        $_totalFullsalary=0;
        $_tax=0;
        $_totalsalary=0;
        $_totaladvance=0;
        $_returned=0;
    @endphp
    <tbody>
        @foreach ($data as $employee)

        <tr>
            <td>
                {{++$i}}
                @if (!$employee->old)
                    <input type="hidden" name="employees[]" value="{{$employee->id}}">
                @endif
            </td>
            <td>
                {{$employee->name}}
            </td>
            <td>
                @if ($employee->prevbalance>0)

                    {{$employee->prevbalance}}
                    @php
                        $_prevbalance+=$employee->prevbalance;
                    @endphp
                @else
                0
                @endif
            </td>
            <td>
                @if ($employee->prevbalance<0)

                    {{-1*$employee->prevbalance}}
                    @php
                        $_prevSalary+=(-1*$employee->prevbalance);
                    @endphp
                @else
                0
                @endif
            </td>
            @if (env('use_employeetax'))
                <td>
                    {{$employee->salary[1]}}
                    @php
                        $_totalFullsalary+=($employee->salary[1]);
                    @endphp
                </td>
                <td>
                    {{$employee->salary[0]}}
                    @php
                    $_tax+=($employee->salary[0]);
                @endphp
                </td>
            @endif
            <td>
                @php
                    $sal=$employee->salary[1]-$employee->salary[0];
                @endphp
                {{$sal}}
                @php
                    $_salary+=$sal;
                @endphp
            </td>
            <td>
                {{$employee->paid}}
                @php
                    $_paid+=$employee->paid;
                @endphp
            </td>
            <td>
                {{$employee->advance}}
                @php
                    $_advance+=$employee->advance;
                @endphp
            </td>
            <td>
                {{$employee->returned}}
                @php
                    $_returned+=$employee->returned;
                @endphp
            </td>

            @php

                $t=$employee->prevbalance-($sal-$employee->advance-$employee->paid+$employee->returned);
            @endphp
            <td>
                {{$t<0?(-1*$t):0}}
                @php
                    $_totalsalary+=$t<0?(-1*$t):0;
                @endphp

            </td>
            <td>
                {{$t>0?$t:0}}
                @php
                    $_totaladvance+=$t>0?$t:0;
                @endphp
            </td>

            <td>
                {{$employee->acc}}
            </td>
            <td></td>

        </tr>
        @endforeach
        <tr class="font-weight-bold">
            <td colspan="2">
                Total
            </td>
            <td>
                {{$_prevbalance}}

            </td>
            <td>
                {{$_prevSalary}}

            </td>
            @if (env('use_employeetax'))
            <td>
                {{$_totalFullsalary}}
            </td>
            <td>
                {{$_tax}}
            </td>
            @endif
            <td>
                {{$_salary}}
            </td>
            <td>
                {{$_paid}}
            </td>
            <td>
                {{$_advance}}
            </td>
            <td>
                {{$_returned}}
            </td>

            <td>
                {{$_totalsalary}}
            </td>
            <td>
                {{$_totaladvance}}
            </td>
            <td>------</td>
            <td></td>
        </tr>
    </tbody>
</table>
{{-- <div class="p-4 d-print-none">
    <input type="submit" value="Update Records" class="btn btn-success btn-sm" >
</div> --}}

</form>
