<table class="table table-bordered">
    <thead>
        <tr>
            <th>Date</th>
            <th>Collected</th>
            <th>Sales</th>
            <th>Raw Material</th>
            <th>Manufactured</th>
            <th>Wastage</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($managedData as $key=>$data)
            <tr>
                <th>
                    {{_nepalidate($key)}}
                </th>
                <td>
                    {{$data->where('type','collect')->sum('qty')}}
                </td>
                <td>
                    {{$data->where('type','sell')->sum('qty')}}
                </td>
                <td>
                    {{$data->where('type','raw')->sum('qty')}}
                </td>
                <td>
                    {{$data->where('type','manu')->sum('qty')}}
                </td>
                <td>
                    {{$data->where('type','waste')->sum('qty')}}
                </td>
            </tr>
            
        @endforeach
    </tbody>


</table>
