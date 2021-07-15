<div class="table-responsive" >
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Item Number</th>
                <th>Item Name</th>
            </tr>
        </thead>
        <tbody id="itemData">
            @foreach(\App\Models\Item::where('farmeronly',1)->select('number','sell_price','title')->get() as $i)
            <tr id="item-{{ $i->number }}" data-rate="{{$i->sell_price}}" data-number="{{ $i->number }}" data-name="{{ $i->title }}" onclick="itemSelected(this.dataset);">
                <td class="p-1"><span style="cursor: pointer;">{{ $i->number }}</span></td>
                <td class="p-1"><span style="cursor: pointer;">{{ $i->title }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
