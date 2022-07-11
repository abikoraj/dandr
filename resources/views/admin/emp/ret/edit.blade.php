<div class="container pt-2">
    <form action="{{route('admin.employee.ret.update')}}" onsubmit="return update(this,event,{{$advance->id}})">
        @csrf
        <input type="hidden" name="id" id="eid" value="{{$advance->id}}" >
        <div class="row">
            <div class="col-md-6">
                <label for="etitle">Title</label>
                <input type="text" name="title" id="etitle" class="form-control" value="{{$advance->title}}">
            </div>
            <div class="col-md-3">
                <label for="eamount">Amount</label>
                <input type="number" name="amount" id="e_amount" class="form-control expay_handle" value="{{$advance->amount}}">
            </div>
            <div class="col-md-3 pt-1 pt-md-4">
                <button class="btn btn-success">Update Return</button>
            </div>
            <div class="col-12">
                <div class="row">
                    @include('admin.payment.editholder')
                </div>
            </div>
        </div>
    </form>
</div>
