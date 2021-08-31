@extends('admin.layouts.app')
@section('title', 'Counters Day Management')
@section('head-title')
    <a href="{{ route('admin.counter.home') }}">Counters</a> / Day Management
@endsection
@section('toobar')
@endsection
@section('content')


    <div>
        <form action="{{route('admin.counter.day.open')}}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-2 pr-0 d-flex align-items-center">

                    <label for="date" class="w-100 text-right">Date:</label>
                </div>
                <div class="col-md-4 pl-1">
                    <input type="text" class="form-control" name="date" id="date"
                        {{ $setting == null ? '' : ($setting->open ? 'disabled' : '') }} value="{{ $setting == null ? '' : ($setting->open ? _nepalidate($setting->date) : '') }}">
                </div>
                <div class="col-md-3 d-flex align-items-center">

                    <input {{ $setting == null ? '' : ($setting->open ? 'disabled' : '') }} {{ $setting == null ? '' : ($setting->direct ? 'checked' : '') }} type="checkbox" name="direct" id="direct" class="mr-2" value="1"> <label for="direct"
                        class="mb-0 pb-0">Open Directly</label>

                </div>
                <div class="col-md-3 d-flex align-items-center">
                    @if ($setting == null)
                        <button class="btn btn-primary w-100">Open Day</button>
                    @else
                        @if ($setting->open)
                            <button class="btn btn-primary w-100">Close Day</button>
                        @else
                            <button class="btn btn-primary w-100">Open Day</button>

                        @endif
                    @endif
                </div>
            </div>
        </form>
    </div>
    <hr>
    @if ($setting!=null)
        @if ($setting->open)
            <table class="table table-bordered">
                <tr >
                    <th>Counter</th>
                    <th>Request Amount</th>
                    <th></th>
                </tr>
                @foreach ($setting->requests() as $req)
                    <tr id="req-{{$req->id}}">
                        <th>
                            {{$req->counter->name}}
                        </th>
                        <td>
                            {{$req->request}}
                        </td>
                        <td>
                            <input id="req-amount-{{$req->id}}" type="number" min="0" value="{{$req->request}}" class="">
                            <button class="btn btn-sm btn-primary" onclick="approveAmount({{$req->id}})">Approve Amount</button>
                        </td>
                    </tr>
                @endforeach
            </table>
        @endif
    @endif

@endsection
@section('js')
    <script>
        lock = false;
        @if ($setting == null)
            setDate('date',true);
        @else
            @if (!$setting->open)
                setDate('date',true);
            @endif
        @endif

        function approveAmount(id){
            if(confirm('DO You Accecpt Counter Opening Request')){

                showProgress('Approving Amount');
                axios.post('{{route('admin.counter.day.approve')}}',{
                    "id":id,
                    "amount":$('#req-amount-'+id).val()
                })
                .then((res)=>{
                    hideProgress();
                    $('#req-'+id).remove();
                })
                .catch((err)=>{
                    hideProgress();
                    showNotification('bg-danger',"Cannot Accecpt Request Please Try Again");
                });
            }
        }
    </script>
@endsection
