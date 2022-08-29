@foreach ($sell_items as $sell_item)
    @include('admin.emp.sales.single',['sell_item'=>$sell_item])
@endforeach