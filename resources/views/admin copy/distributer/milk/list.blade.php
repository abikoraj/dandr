@foreach ($milkDatas as $milkData)
    @include('admin.distributer.milk.single',['milkData'=>$milkData])
@endforeach