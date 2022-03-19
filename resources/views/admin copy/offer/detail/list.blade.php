@foreach ($items as $item)
    @include('admin.offer.detail.singleitem',['item'=>$item]);
@endforeach