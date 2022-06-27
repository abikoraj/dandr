@extends('admin.layouts.app')
@section('title','Extra Income - Add')
@section('head-title')
<a href="{{route('admin.accounting.extra.income.index')}}">
    Extra Income
</a>
/ Add
@endsection
@section('content')
    {!! renderEmpList()!!}
    <form action="{{route('admin.accounting.extra.income.add')}}" method="post" id="addExtraIncomeForm">
        @csrf
        <div class="row">
            <div class="col-md-3">
                <label for="extra_income_category_id">Category</label>
                <select name="extra_income_category_id" id="extra_income_category_id" class="form-control ms">
                    @foreach ($cats as $cat)
                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3"><label for="date">Date</label><input type="text" required name="date" id="date" class="form-control calender"></div>

            <div class="col-md-6"><label for="title">Title</label><input type="text" name="title" id="title" class="form-control"></div>
            <div class="col-md-3"><label for="amount">Amount</label><input type="number" required name="amount" id="amount" class="form-control"></div>
            <div class="col-md-9"><label for="received_by">Received By</label><input list="emp_datalist" type="text" name="received_by" id="received_by" class="form-control"></div>
            <div class="col-12">
                <label for="payment_detail">Payment Detail</label>
                <textarea name="payment_detail" id="payment_detail" class="form-control"></textarea>
            </div>
            <div class="col-12 pt-2">
                <button class="btn btn-primary">Save Extra Income</button>
            </div>
        </div>
    </form>

@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $('#addExtraIncomeForm').submit(function (e) {
                e.preventDefault();
                showProgress('Adding Extra income');
                axios.post(this.action,new FormData(this))
                .then((res)=>{
                    hideProgress();
                    this.reset();
                    showNotification('bg-success','Extra Income Addded Successfully');
                })
                .catch((err)=>{
                    showNotification('bg-danger','Some Error Occured Please Try again');
                    hideProgress();
                });
            });
        });
    </script>
@endsection
