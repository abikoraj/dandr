<style>
    td,th{
        border:1px solid black;
    }
    @media print {
        td{
            font-weight:700;
        }
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

<table class="table">
    <tr>
        <th>NO</th>
        <th>Name</th>
        <th>Total Bonus (Rs.)</th>
    </tr>
    @php
        $sum=0;
    @endphp
    @foreach ($farmers as $farmer)
        <tr>
            <td>
                {{$farmer->no}}
            </td>
            <td>
                {{$farmer->name}}
            </td>
            <td>
                {{$farmer->sum}}
            </td>
        </tr>
        @php
            $sum+=$farmer->sum;
        @endphp
    @endforeach
    <tr>
        <th colspan="2" class="text-right">
            Total
        </th>
        <th>
            {{$sum}}
        </th>
    </tr>
</table>
