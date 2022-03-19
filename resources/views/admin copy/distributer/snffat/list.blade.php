@foreach ($milkDatas as $milkData)
    @include('admin.distributer.snffat.single',['milkData'=>$milkData])
@endforeach