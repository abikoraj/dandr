<style>
    tr,td,th{
        border:1px solid black;
        padding:5px 7px !important;

    }
</style>
<h5 class="p-2">
    {{$title}}
</h5>
<table class="table">
    <thead>

        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Name</th>
            <th colspan="3">Morning</th>
            <th colspan="3">Evening</th>  
        </tr>
        <tr>
            <th>Milk</th>
            <th>Fat</th>
            <th>snf</th>
            <th>Milk</th>
            <th>Fat</th>
            <th>snf</th>
        </tr>
    </thead>
    <tbody>

        @foreach ($farmers as $farmer)
        <tr>
            <td>
                {{$farmer->no}}
            </td>
            <td>
                {{$farmer->name}}
            </td>
            <td>
    
            </td>
            <td>
    
            </td>
            <td>
    
            </td>
            <td>
    
            </td>
            <td>
    
            </td>
            <td>
    
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
