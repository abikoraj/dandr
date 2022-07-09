<div class="p-2">
    <form action="{{route('admin.farmer.milk.payment.update',['id'=>$payment->id])}}" method="post" onsubmit="return update(this,event,{{$payment->id}});">
        @csrf
        <div class="row">
            <div class="col-md-3">
                {{ $payment->no }}
            </div>
            <div class="col-md-3">
                {{ _nepalidate($payment->date) }}

            </div>
            <div class="col-md-3">
                {{ $payment->name }}
            </div>
            <div class="col-md-3">
                <input required  id="amount-{{ $payment->id }}" type="number" min="1"
                    class="form-control expay_handle" name="amount" value="{{ (float) $payment->amount }}">
            </div>
            <div class="col-md-3">
                <button class="btn btn-success btn-sm" >
                    Update
                </button>

            </div>
           @include('admin.payment.editholder')
        </div>
    </form>

</div>
