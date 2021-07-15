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
<form action="{{route('report.emp.session')}}" method="POST">
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
                Advance
            </th>
            <th>
                Salary
            </th>
            <th>
                Remaning Salary
            </th>
            <th>
                Remaning Balance
            </th>
            <th>
                Bank Detail
            </th>
        </tr>
    </thead>
    @php
        $i=0;
        $_prevbalance=0;
        $_advance=0;
        $_salary=0;
        $_totalsalary=0;
        $_totalbalance=0;
    @endphp
    <tbody>
        @foreach ($data as $employee)
        @php
            $user = \App\Models\User::where('id',$employee->user_id)->first();
        @endphp
        @if ($user!=null)

        <tr>
            <td>
                {{++$i}}
                @if (!$employee->old)
                    <input type="hidden" name="employees[]" value="{{$employee->toJson()}}">
                @endif
            </td>
            <td>
                {{$user->name}}
            </td>
            <td>
                {{$employee->prevbalance}}
                @php
                    $_prevbalance+=$employee->prevbalance;
                @endphp
            </td>
            <td>
                {{$employee->advance}}
                @php
                    $_advance+=$employee->advance;
                @endphp
            </td>
            <td>
                {{$employee->salary}}
                @php
                    $_salary+=$employee->salary;
                @endphp
            </td>
            @php

                $t=$employee->salary-$employee->prevbalance-$employee->advance;
            @endphp
            <td>
                {{$t>0?$t:0}}
                @php
                    $_totalsalary+=$t>0?$t:0;
                @endphp
            </td>
            <td>
                {{$t<0?(-1*$t):0}}
                @php
                    $_totalbalance+$t<0?(-1*$t):0;
                @endphp
            </td>

            <td>
                {{$employee->acc}}
            </td>

        </tr>
        @endif
        @endforeach
        <tr class="font-weight-bold">
            <td colspan="2">
                Total
            </td>
            <td>
                {{$_prevbalance}}

            </td>
            <td>
                {{$_advance}}
            </td>
            <td>
                {{$_salary}}
            </td>
            <td>
                {{$_totalsalary}}
            </td>
            <td>
                {{$_totalbalance}}
            </td>
            <td>------</td>
        </tr>
    </tbody>
</table>
{{-- <div class="p-4 d-print-none">
    <input type="submit" value="Update Records" class="btn btn-success btn-sm" >
</div> --}}

</form>
