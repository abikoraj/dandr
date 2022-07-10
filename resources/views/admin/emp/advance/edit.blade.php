<div class="container pt-2">
    <form action="{{route('admin.employee.advance.update')}}" method="post" onsubmit="return update(this,event,{{$advance->id}})">
        @csrf
        <div class="row">
            <div class="col-md-9">
                <label for="title">Title</label>
                <input type="text" name="title" id="etitle" class="form-control" value="{{$advance->title}}">
            </div>
            <div class="col-md-3">
                <label for="amount">Amount</label>
                <input type="text" name="amount" id="eamount" class="form-control expay_handle" value="{{$advance->amount}}">
            </div>

            @include('admin.payment.editholder')
        </div>
    </form>
</div>
