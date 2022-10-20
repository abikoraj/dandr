@extends('admin.layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}">
@endsection
@section('title')
    Reconciliation
@endsection
@section('head-title')
  Reconciliation
@endsection

@section('content')
    <form action="{{ route('admin.reconciliation.add') }}" onsubmit="return save(this,event);">
        @csrf
        <div class="row">
            <div class="mb-2 col-md-3">
                <label for="date">Date</label>
                <input type="text" name="date" id="date" class="form-control calender" required>
            </div>
            <div class="mb-2 col-md-3">
                <label for="from_user_id" class="d-flex justify-content-between">
                    <span>Farmers</span>
                     <span id="from_user_id_amount"></span> </label>
                <select name="farmer_user_id" id="from_user_id" class="form-control ms" required></select>
            </div>

            <div class="mb-2 col-md-3">
                <label for="amount">Amount</label>
                <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
            </div>
            <div class="mb-2 col-md-3">
                <label> Type</label>
                <select name="type" class="form-control ms">
                    <option value="1">Cr.</option>
                    <option value="2">Dr.</option>
                </select>
            </div>

            <div class="mb-2 col-12">
                <button class="btn btn-primary">
                    Save
                </button>
                <span class="btn btn-danger" onclick="reset(1)">
                    Cancel
                </span>
            </div>

        </div>
    </form>
@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script>
        const users = {!! json_encode($users) !!};
        console.log(users);
        $(document).ready(function() {
            const html = (`<option></option>`)+(users.map(o => `<option value="${o.user_id}">${o.name}</option>`).join(''));
            $('#from_user_id').html(html);
            $('#from_user_id').select2();
            $('#from_user_id').change(function(e){
                $('#from_user_id_amount').html('');
                if(this.value==''){
                    return;
                }
                getUserBalance(this.value)
                .then((amount)=>{
                    $('#from_user_id_amount').html(getDRCR(amount));
                })
                .catch((err)=>{
                    console.log(err);
                });
            });
            $('#to_user_id').change(function(e){

                $('#to_user_id_amount').html('');
                if(this.value==''){
                    return;
                }
                getUserBalance(this.value)
                .then((amount)=>{

                    $('#to_user_id_amount').html(getDRCR(amount));


                })
                .catch((err)=>{
                    console.log(err);
                });
            });
        });


        function reset(type){
            if(type==1){
                if(!(confirm('Do you want to clear all Data'))){
                    return;
                }
            }
            $('#from_user_id').val(null).change();
            $('#to_user_id').val(null).change();
            $('#to_user_id_amount').html('');
            $('#from_user_id_amount').html('');
            $('#amount').val(null);
            $('#detail').val(null);

        }
        function save(ele, e) {
            e.preventDefault();
            showProgress('Saving Data');
            axios.post(ele.action, new FormData(ele))
            .then((res) => {
                    if (res.data.status) {
                        successAlert('Reconciliation Added Sucessfully');
                        reset(2);
                    } else {
                        errAlert({},"Cannot save Reconciliation");
                    }
                })
                .catch(err => {
                    errAlert(err);
                });
        }
    </script>
@endsection
