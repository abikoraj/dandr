<span class="d-none"  id="single-js">
    <xxx_tr id="item-xxx_id" data-name="xxx_title">
        <xxx_td>xxx_title</xxx_td>
        <xxx_td>xxx_number</xxx_td>
        <xxx_td>xxx_sell_price</xxx_td>
        <xxx_td>xxx_stock</xxx_td>
        <xxx_td>xxx_unit</xxx_td>
        <xxx_td>xxx_reward_percentage</xxx_td>
        <xxx_td>
            <button  class="btn btn-primary btn-sm"  onclick="initEdit(xxx_id);" >Edit</button>
            @if(env('multi_stock',false))
                <a href="{{route('admin.item.center-stock',['id'=>'xxx_id'])}}" class="btn btn-primary btn-sm"  >Stock</a>
            @endif
            <button class="btn btn-danger btn-sm" onclick="removeData(xxx_id);">Delete</button>
        </xxx_td>
    </xxx_tr>
</span>