@extends('admin.layouts.app')
@section('title', 'Customers')
@section('head-title', 'Customers')
{{-- @section('css')
    <style>
        #pagination {
            position: fixed;
            bottom: 10px;
            right: 50px;
            left: 250px;
            background: white;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.25);
            padding: 5px;
        }

        .pagination-input {
            padding: 8px;
            border-radius: 5px;
            width: 70px;
        }
        #result{
            position: fixed;
            top:0;
            background: red;
            z-index: 4;
            width:500px;
        }

    </style>
@endsection --}}
@section('toobar')
    <button type="button" class="btn btn-primary waves-effect m-r-20" data-toggle="modal" data-target="#addModal">New
        Customer</button>
@endsection
@section('content')

    <div class="pt-2 pb-2" >
        <input type="text" oninput="search(this)"  class="w-50" placeholder="Search with name or phone number">
    </div>

    @include('admin.customer.add')
    @include('admin.customer.edit')
    <div class="table-responsive">
        <table class="table table-bordered  dataTable">
            <thead>
                <tr>
                    <th>#CUS Id</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th class="w-25">Address</th>
                    <th>Points</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="data">


            </tbody>
        </table>
    </div>
    <div id="pagination" class="row justify-content-center">

    </div>


    @include('admin.customer.singlejs')

@endsection
@section('js')


    <script>
        var customers = [];
        var allData=[];
        lock = false;

        const countStep = {{ env('countstep', 24) }};
        var step = 0;
        var $count = 0;
        var total = 0;

        function del(id) {
            if (!lock) {
                if (prompt('Please Type Yes To Delete').toLowerCase() == "yes") {

                    lock = true;
                    showProgress('Deleting Customer');
                    var data = {
                        'id': id
                    };
                    axios.post('{{ route('admin.customer.del') }}', data)
                        .then((res) => {

                            $('#customer_' + id).remove();
                            showNotification('bg-success','Customer Delete Sucessfully');
                            hideProgress();
                            lock = false;
                        })
                        .catch((err) => {
                            hideProgress();
                            showNotification('bg-danger','Customer cannot be deleted,Please try again.');
                            lock = false;
                        })
                }
            }
        }


        function loadData() {
            if (!lock) {
                lock = true;
                showProgress("Loading Customers");
                axios.post('{{ route('admin.customer.home') }}', {})
                    .then((res) => {
                        allData=res.data;
                        customers = res.data;
                        // console.log(customers);
                        render(customers.slice(step * countStep, countStep));
                        renderPagination(customers.length);
                        lock = false;
                        hideProgress();
                    })
                    .catch((err) => {
                        lock = false;
                        console.log(err);
                        hideProgress();
                    });
            }
        }
        //reset
        function reset() {
            $('#search-name').val('');
            $('#search-phone').val('');
            _name = '';
            phone = '';
            step = 0;
            loadData();
        }

        function render(datas) {
            console.log('datas', datas, datas.length);
            html = '';

            _r = $('#single-js').html();
            _r = _r.replaceAll('xxx_tr', 'tr');
            _r = _r.replaceAll('xxx_td', 'td');
            // console.log()
            for (let index = 0; index < datas.length; index++) {
                const data = datas[index];

                rendered = _r.replaceAll('xxx_id', data.id);
                rendered = rendered.replaceAll('xxx_name', data.name);
                rendered = rendered.replaceAll('xxx_phone', data.phone);
                rendered = rendered.replaceAll('xxx_address', data.address);
                rendered = rendered.replaceAll('xxx_points', data.points ?? '0');
                rendered = rendered.replaceAll('XXX_user_id', data.user_id);
                rendered = rendered.replaceAll('XXX_basicInfo', encodeURIComponent(JSON.stringify(data)));
                // rendered = rendered.replaceAll('xxx_reward_percentage', data.reward_percentage);
                html += rendered;
                console.log(rendered);
            }
            $('#data').html(html);

        }

        function show(_step) {
            step = _step;
            console.log(step);
            render(customers.slice(step * countStep,(step * countStep)+ countStep));
            renderPagination(customers.length);
        }

        function loadStep() {
            step = parseInt($('#pagination-input').val()) - 1;

            if (step < 1) {
                alert('Please Chose Page Higher Than 0');
                return;
            } else if (step > total) {
                alert('Please Chose Page Lower Than ' + total);
                return;
            } else {

                loadData();
            }
        }

        function renderPagination(count) {
            $count = count;
            total = parseInt($count / countStep) + ($count % countStep != 0 ? 1 : 0);
            // console.log(total);
            $('#pagination').html('')
            $('#pagination').removeClass('d-none');

            if (total > 1) {
                $('#pagination').addClass('row');
                if (total > 10) {
                    _min = step - 8;
                    _max = step + 8;
                    if (_min < 0) {
                        _max = _max - _min;
                        _min = 0;
                    }
                    if (_max > total) {
                        _rem = _max - total;
                        _max = total;
                        _min -= _rem;
                    }
                    for (let index = _min; index < _max; index++) {
                        if (index == step) {
                            $('#pagination').append('<button class="btn btn-primary mx-1 " onclick="show(' + index + ')">' +
                                (
                                    index + 1) + '</button>')
                        } else {

                            $('#pagination').append('<button class="btn btn-outline-primary mx-1 " onclick="show(' + index +
                                ')">' + (index + 1) + '</button>')
                        }
                    }
                    $('#pagination').append(
                        '<div><input id="pagination-input" class="pagination-input"><button class="btn btn-primary mx-1 " onclick="loadStep()">go</button>'
                    );

                    $('#pagination').append('<button class="btn btn-outline-primary mx-1 " onclick="show(0)">First</button>');
                    $('#pagination').append('<button class="btn btn-outline-primary mx-1 " onclick="show('+(total-1)+')">Last</button></div>');



                } else {
                    for (let index = 0; index < total; index++) {
                        if (index == step) {
                            $('#pagination').append('<button class="btn btn-primary mx-1 " onclick="show(' + index + ')">' +
                                (
                                    index + 1) + '</button>')

                        } else {

                            $('#pagination').append('<button class="btn btn-outline-primary mx-1 " onclick="show(' + index +
                                ')">' + (index + 1) + '</button>')
                        }
                    }
                }
            } else {
                $('#pagination').addClass('d-none');

            }
        }

        function search(ele){
            step=0;
            key=ele.value.toLowerCase();
            customers=allData.filter(o=>o.name.toLowerCase().startsWith(key)|| o.phone.startsWith(key));
            render(customers.slice(step * countStep,(step * countStep)+ countStep));
            renderPagination(customers.length);
        }
        loadData();
    </script>
@endsection
