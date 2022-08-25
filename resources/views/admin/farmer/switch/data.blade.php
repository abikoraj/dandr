<style>
    tr.selfarmer{
        cursor: pointer;

    }
</style>
<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>Name</th>
        <th> Enabled </th>
    </tr>
    @foreach ($farmers as $farmer)
        <tr onclick="sel({{$farmer->id}})" class="selfarmer" >
            <td>{{$farmer->no}}</td>
            <td>{{$farmer->name}}</td>
            <td>
                <input type="hidden" value="{{$farmer->id}}" class="farmer">
                <input type="checkbox" id="checkbox_{{$farmer->id}}" data-id="{{$farmer->id}}" class="check-farmer" {{$farmer->enabled==1?"checked":""}}>
            </td>
        </tr>
    @endforeach
</table>
<div class="p-1 text-right">
    <button class="btn btn-primary " onclick="save()">Save Farmers</button>
</div>