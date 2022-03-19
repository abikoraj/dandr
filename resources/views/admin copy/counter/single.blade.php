<div class="col-md-4">
    <div class="card shadow ">
        <div class="p-2 ">
           @if(!$counter->hasStatus())
                <form action="{{route('admin.counter.update',['id'=>$counter->id])}}" method="post">
                    @csrf
                    <input type="text" id="name" name="name" value="{{$counter->name}}" class="form-control next" data-next="phone" placeholder="Enter Counter name" required>
                    <div class="row m-0">
                        <div class="col-md-6 p-1">
                            <button class="btn btn-primary w-100">Update</button>
                        </div>
                        @if (!$counter->hasBill())
                            <div class="col-md-6 p-1">
                                <button class="btn btn-danger w-100">Delete</button>
                            </div>
                        @endif
                    </div>
                </form>
           @else
           <div class="text-center">
               <b>
                   {{$counter->name}}
               </b>
               <hr class="mt-0">
               @php
                   $status=$counter->currentStatus();
               @endphp
               <div id="status-{{$counter->id}}">
                   @include('admin.counter.status',['status'=>$status])
               </div>
           </div>
           @endif
        </div>

    </div>
</div>
