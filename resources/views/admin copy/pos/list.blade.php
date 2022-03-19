@foreach ($bills as $bill)
    @include('admin.pos.single',['bill'=>$bill])
@endforeach