 @foreach ($counters as $counter)
     @include('admin.counter.single',['counter'=>$counter,'date'=>$date])
 @endforeach
