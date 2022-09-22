@extends('admin.layouts.app')
@section('title', 'Customers - account openings')
@section('head-title')
    <a href="{{ route('admin.customer.home') }}">Customers</a>
    / Account Openings
@endsection
@section('css')
    <link rel="stylesheet" href="{{asset('backend/plugins/select2/select2.css')}}">
@endsection

@section('content')

   

    <form action="{{route('admin.customer.opening')}}" method="POST" id="add">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="date">Date</label>
                    <input readonly type="text" name="date" id="nepali-datepicker" class="calender form-control "
                         placeholder="Date" >
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-md-3">
                <div class="form-group">
                    <label for="id">Customer</label>
                    <select name="user_id" id="user_id" class="form-control ms">

                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="number" required id="amount" name="amount" class="form-control " >

                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="type">Type</label>
                    <select name="type" id="type" class="form-control show-tick ms ">
                        <option value="1">CR</option>
                        <option value="2">DR</option>
                    </select>

                </div>
            </div>

            <div class="col-md-3 mt-4">
                <button class="btn btn-primary btn-block">Save</button>
            </div>

        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered  dataTable">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="data">
                @foreach ($openings as $opening)
                    @include('admin.customer.opening.single', ['opening' => $opening])
                @endforeach

            </tbody>
        </table>
    </div>
    



@endsection
@section('js')

    <script src="{{asset('backend/plugins/select2/select2.min.js')}}"></script>
    <script>
        const customers={!! json_encode($customers) !!};
        $(document).ready(function () {
            $('#user_id').html(
                customers.map(o=>`<option value="${o.id}">${o.name}</option>`).join('')
            );
            $('#user_id').select2();
        
            $('#add').submit(function (e) { 
                
                e.preventDefault();
                const ele=this;
                showProgress('Adding Account Opening');
                var fd=new FormData(ele);
                const customer=customers.find(o=>o.id==$('#user_id').val());
                fd.append('name',customer.name);
                axios.post(ele.action,fd)
                .then((res)=>{
                    $('#data').append(res.data);
                    hideProgress();
                    showNotification('bg-success','Accoount opening added sucessfully');
                    ele.reset();
                    $('#user_id').val(null).change();
                })
                .catch((err)=>{
                    hideProgress();
                    const msg=err.response?err.response.data.message:'Please try again'
                    showNotification('bg-danger','Accoount opening failed,'+msg);
        
                });
            });
        });

        //TODO make data refresh
        function  loadData() {
            window.location.reload();
        }

        function del(id){
            if(prompt('Enter yes to continue')=='yes'){
                showProgress("Deleting Account opening");
                axios.post("{{route('admin.customer.opening.del')}}",{id:id})
                .then((res)=>{
                    $('#opening-'+id).remove();
                    showNotification('bg-success','Accoount opening deleted.');

                    hideProgress();
                })
                .catch((err)=>{
                    const msg=err.response?err.response.data.message:'Please try again'
                    showNotification('bg-danger','Accoount deleting failed,'+msg);
                    hideProgress();

                })
            }
        }
      
    </script>
@endsection
