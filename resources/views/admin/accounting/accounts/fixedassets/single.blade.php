<tr id="fixedAsset-{{$fixedAsset->id}}">
    <td>
        {{$fixedAsset->category}}
    </td>
    <td>
        {{$fixedAsset->name}}
    </td>
    <td>
        {{$fixedAsset->full_amount}}
    </td>
    <td>
        {{$fixedAsset->amount}}
    </td>
    <td>
        {{_nepalidate($fixedAsset->startdate)}}
    </td>
    <td>
        {{$fixedAsset->depreciation}}
    </td>
    <td>
        {{$fixedAsset->salvage_amount}}
    </td>
    <td>
        <a onclick="initUpdateFixedAsset({{$fixedAsset->id}})"  class="text-success">Update</a> |
        <a onclick="initDel({{$fixedAsset->id}})"  class="text-danger">Del</a>
    </td>
</tr>
