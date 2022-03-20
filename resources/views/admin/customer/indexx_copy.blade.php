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
    @if ($large)
        <div class="row">
            <div class="col-md-3">
                <input type="text" class="form-control"  id="search-name" placeholder="Search using name" oninput="_name=this.value;console.log(this.value,_name);">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control"  id="search-phone" placeholder="Search using Phone" oninput="phone=this.value;">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100" onclick="step=0;loadData()">Search</button>
            </div>
            <div class="col-md-3">
                <button class="btn btn-danger w-100" onclick="reset()">Reset</button>
            </div>
        </div>
        <hr>
    @else
    <div class="pt-2 pb-2">
        <input type="text" id="sid" placeholder="Search">
    </div>
    @endif
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
                    <th>PAN/VAT</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="data">
                {{-- @foreach ($customers as $customer)
                    @include('admin.customer.single',['customer'=>$customer])
                @endforeach --}}

            </tbody>
        </table>
    </div>
    <div id="pagination" class="row justify-content-center">

    </div>


    @include('admin.customer.singlejs')

@endsection
@section('js')

    var d
    <script>
        lock = false;

        countStep = {{ env('countstep', 24) }};
        @if ($large)
            var step=0;
            var _name='';
            var phone='';
            var $count='';
            var total=0;

        @else
            initTableSearch('sid', 'data', ['name']);
            // initTableSearch('sid', 'itemData', ['name']);
        @endif


        function del(id) {
            if (!lock) {
                if (prompt('Please Type Yes To Delete').toLowerCase() == "yes") {

                    lock = true;
                    showProgress('Updating Customer');
                    var data = {
                        'id': id
                    };
                    axios.post('{{ route('admin.customer.del') }}', data)
                        .then((res) => {

                            $('#customer_' + id).replaceWith(res.data);
                            hideProgress();
                            lock = false;
                            $('#editModal').modal('hide');
                            document.getElementById('editCustomer').reset();
                        })
                        .catch((err) => {
                            hideProgress();
                            lock = false;
                        })
                }
            }
        }

        @if($large)
        function loadData() {
            if (!lock) {
                lock = true;
                showProgress("Loading Customers");
                axios.post('{{ route('admin.customer.home') }}', {
                        "step": step,
                        "name": _name != '' ? _name : null,
                        "phone": phone != '' ? phone : null
                    })
                    .then((res) => {
                        console.log(res);
                        render(res.data.items);
                        renderPagination(res.data.total);
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
         function  reset() {
            $('#search-name').val('');
            $('#search-phone').val('');
            _name='';
            phone='';
            step=0;
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
                rendered = rendered.replaceAll('xxx_panvat', data.panvat??'');
                rendered = rendered.replaceAll('XXX_user_id', data.user_id);
                rendered = rendered.replaceAll('XXX_basicInfo', encodeURIComponent(JSON.stringify(data)));
                // rendered = rendered.replaceAll('xxx_reward_percentage', data.reward_percentage);
                html += rendered;
                console.log(rendered);
            }
            $('#data').html('');
            $('#data').append(html);
            console.log(html);

        }

        function show(_step) {
            step = _step;
            loadData();
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
            console.log(total);
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
                        '<div><input id="pagination-input" class="pagination-input"><button class="btn btn-primary mx-1 " onclick="loadStep()">go</button><div>'
                        );


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

        $('#search-name').keydown(function (e) {

            if(e.which==13 && _name.length>2){
                step=0;
                loadData();
            }
        });

        $('#search-phone').keydown(function (e) {

            if(e.which==13 && phone.length>4){
                step=0;
                loadData();
            }
        });

        loadData();
        @endif
    </script>
@endsection
