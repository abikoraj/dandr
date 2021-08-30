@extends('pos.layout.index')
@section('content')
    <div class="container py-4">

        <div class="card shadow p-3">
            <h4 class="text-center">
                Counter Opening
            </h4>
            <hr>
            @if ($status!=null)
            <h4 class="text-center">
                Request Amount Not Approved.
            </h4>
            <hr>
            <h6 class="py-2 d-flex justify-content-center">
                {{-- <a href="{{route('pos.index')}}" class="mx-2">COUNTERS</a> --}}
                <a href="{{route('pos.index')}}" class="mx-2 ">GOTO POS INTERFACE </a>
            </h6>

            @else
                <form action="{{route('pos.counter.open')}}" method="POST">
                    @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <label for="amount">Opening Amount</label>
                            <input type="number" name="amount" id="amount" class="form-control mb-2" required min="0">
                            @if ($setting->direct)
                            <button class="btn btn-primary w-100">Open Counter</button>
                            @else
                            <button class="btn btn-primary w-100">Request Counter Amount</button>

                            @endif
                            
                        </div>
                    </div>
                </form>  
                
            @endif
        </div>
    </div>
@endsection
