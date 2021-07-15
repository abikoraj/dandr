@extends('admin.layouts.app')
@section('title', 'Expenses')
@section('head-title', 'Expenses')
@section('css')
    <link rel="stylesheet" href="{{ asset('calender/nepali.datepicker.v3.2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}" />
@endsection
@section('toobar')

    @include('admin.expense.add')
    <div class="col lg-2">
        <div class="pt-2 pb-2">
            <button class="btn btn-primary mr-1" data-toggle="modal" data-target="#addModal">Add Expense</button>
            <a href="{{route('admin.expense.category')}}" class="btn btn-secondary">Expense Categories</a>
        </div>
    </div>
@endsection
@section('content')

    <div class="row mb-3">
        <div class="col lg-12">
            <select name="cat_id" id="cat" class="form-control show-tick ms select2" >
                <option value="-1">All</option>
                @foreach (\App\Models\Expcategory::all() as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
            <small>Select category to display category wise expense</small>
        </div>
    </div>
    @include('admin.layouts.daterange')
    <div class="row">
        <div class="col-md-3">
            <span class="btn btn-primary w-100" onclick="loadExp()"> Load Expenses</span>
        </div>

    </div>
    <hr>
    <div class="table-responsive" id="expenseData">
        
    </div>






@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script>
        // TODO expenses
        function initEdit(title, id) {
            win.showPost("Edit Expense - " + title, '{{ route('admin.expense.edit') }}', {
                "id": id
            })
        }

        function saveData(e) {
            e.preventDefault();
            var bodyFormData = new FormData(document.getElementById('form_validation'));
            axios({
                    method: 'post',
                    url: '{{ route('admin.expense.add') }}',
                    data: bodyFormData,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function(response) {
                    console.log(response);
                    showNotification('bg-success', 'Expense added successfully!');
                    add_another = document.getElementById('add_another').checked;
                    if (!add_another) {
                        debugger;
                        $("#addModal").modal('hide');
                    }
                    $('#form_validation').trigger("reset");
                    $('#expenseData').prepend(response.data);
                    document.getElementById('add_another').checked = add_another;

                })
                .catch(function(response) {
                    //handle error
                    console.log(response);
                });
        }

        // edit data
        function editData(e) {
            e.preventDefault();
            var rowid = $('#eid').val();
            var bodyFormData = new FormData(document.getElementById('editform'));
            axios({
                    method: 'post',
                    url: '{{ route('admin.expense.update') }}',
                    data: bodyFormData,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(function(response) {
                    win.hide();
                })
                .catch(function(response) {
                    //handle error
                    console.log(response);
                
                });
        }



        // delete
        function removeData(id) {
            var dataid = id;
            if (confirm('Are you sure?')) {
                axios({
                        method: 'post',
                        url: '{{ route('admin.expense.delete') }}',
                        data: {
                            "id": id
                        }
                    })
                    .then(function(response) {
                        // console.log(response.data);
                        $('#expense-' + dataid).remove();
                        showNotification('bg-danger', 'Deleted Successfully !');
                    })
                    .catch(function(response) {

                        showNotification('bg-danger', 'You do not have authority to delete!');
                        //handle error
                        console.log(response);
                    });
            }
        }

        window.onload = function() {
            $('#type').val(1).change();
            loadExp();
        };





        function loadExp() {
            $('#expenseData').html('');
            var data={
                'year':$('#year').val(),
                'month':$('#month').val(),
                'session':$('#session').val(),
                'week':$('#week').val(),
                'date1':$('#date1').val(),
                'date2': $('#date2').val(),
                'type':$('#type').val(),
                'cat_id':$('#cat').val()
            };
            axios({
                    method: 'post',
                    url: '{{ route('admin.expense.load') }}',
                    data: data
                })
                .then(function(response) {
                    // console.log(response.data);
                    $('#expenseData').html(response.data);
                    initTableSearch('sid', 'expenseData', ['name']);
                })
                .catch(function(response) {
                    //handle error
                    console.log(response);
                });
        }


        $('#cat').change(function() {
            loadExp();
        });

        s_id = 0;

    </script>
@endsection
