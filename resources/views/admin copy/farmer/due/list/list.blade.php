@foreach ($ledgers as $ledger)
    @include('admin.farmer.due.list.single',['ledger'=>$ledger])
@endforeach

