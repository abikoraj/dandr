<div class="distviwer">
    <div class="pb-3 text-right">
        <span class="btn btn-danger btn-sm" onclick="hideCustomer();">X</span>
    </div>
    <table class="w-100 prodtable">
        <tr>
            <th>Itemno</th>
            <th>Name</th>
        </tr>
        @foreach (\App\Models\Distributer::all() as $dis)
        <tr  class="hovertr" id="distri_{{$dis->id}}" data-distributor="{{$dis->toJson()}}" onclick="selectCustomer({{$dis->id}},'{{$dis->user->name}}')">
            <td>{{$dis->id}}</td>
            <td>{{$dis->user->name}}</td>
        </tr>
        @endforeach
    </table>
</div>
