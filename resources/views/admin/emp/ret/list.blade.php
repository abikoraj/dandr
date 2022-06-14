@foreach ($advances as $advance)
    @include('admin.emp.ret.single',['advance'=>$advance]);
@endforeach
