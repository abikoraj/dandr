@foreach ($ledgers as $d)
    @include('admin.distributer.balance.single',['d'=>$d])
@endforeach
