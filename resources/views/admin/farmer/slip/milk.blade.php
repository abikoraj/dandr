<style>
    tr,td,th{
        border:1px solid black;
        padding:5px 7px;
    }
</style>
<h5 class="p-2">
    {{$title}}
</h5>
<table class="table">
    <thead>

        <tr>
            <th>No</th>
            <th>Name</th>
            <th >Morning</th>
            <th >Evening</th>  
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
            </tr>
        @endforeach
    </tbody>
   
</table>
