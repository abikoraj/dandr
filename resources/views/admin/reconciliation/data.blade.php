@foreach ($datas as $data)
    <tr id="recon-{{$data->id}}">
        <td>{{_nepalidate($data->date)}}</td>
        <td>{{ $data->name }}</td>
        <td>{{ $data->amount }}</td>
        <td>{{ $data->type==1? 'Cr.':'Dr.'}}</td>
        <td><span onclick="deleteData({{$data->id}});"  class="btn btn-danger btn-sm">Delete</span></td>
    </tr>
@endforeach
