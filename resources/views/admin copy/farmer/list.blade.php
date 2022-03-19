@foreach($farmers as $f)
    @include('admin.farmer.single',['user'=>$f])
@endforeach
