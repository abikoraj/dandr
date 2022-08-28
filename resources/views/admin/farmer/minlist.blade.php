<div class="pt-2 pb-2">
    <input type="text" id="sid" placeholder="Search" class="form-control">
</div>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No.</th>
                <th>Farmer Name</th>
            </tr>
        </thead>
        <tbody id="farmerData">
            @foreach($farmers as $u)
            <tr id="farmer-{{ $u->no }}" data-name="{{ $u->name }}" onclick="farmerSelected({{ $u->no }});">
                <td class="p-1"><span style="cursor: pointer;">{{ $u->no }}</span></td>
                <td class="p-1"> <span style="cursor: pointer;">{{ $u->name }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<datalist id="farmerdatalist">
                        
    @foreach($farmers as $u)
    <option value="{{$u->no}}">{{$u->name}}</option>
    @endforeach

</datalist>