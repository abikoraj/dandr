@foreach ($sells as $sell)
    @include('admin.distributer.sell.single',['sell'=>$sell])
@endforeach
