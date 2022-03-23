@extends('admin.layouts.app')
@section('title', 'Items - Branch Stock')
@section('head-title', 'Items - Branch Stock ')

@section('content')

    <div class="pt-2 pb-2">
        <div class="row">
            <div class="col-md-3">
                <select name="center_id" id="center_id" class="form-control ms">
                    @foreach ($centers as $center)
                        <option value="{{ $center->id }}">{{ $center->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" onclick="step=0;loadData();">Load Data</button>
            </div>
            <div class="col-md-2 ">
                <button class="btn btn-danger w-100" onclick="reset()">Reset</button>
            </div>
            <div class="col-5">

            </div>


        </div>
        <hr>
        <div class="row">
            <div class="col-md-3">
                <input type="search" id="sid" placeholder="Search" class="form-control" oninput="filterData();">
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <input type="checkbox" class="mr-1" checked oninput="filterData();" id="good">Good Stock
            </div>

            <div class="col-md-2 d-flex align-items-center">
                <input type="checkbox" class="mr-1" checked oninput="filterData();" id="warning">Warning Stock
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <input type="checkbox" class="mr-1" checked oninput="filterData();" id="error">Error Stock
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <input type="checkbox" class="mr-1" checked oninput="filterData();" id="nostock">No Stock
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="newstable1" class="table table-bordered table-striped table-hover js-basic-example dataTable">
            <thead>
                <tr>
                    <th>Item Name</th>
                    @if (env('use_wholesale', false))
                        <th>Wholesale</th>
                    @endif
                    <th> Price </th>
                    <th>Stock </th>


                </tr>
            </thead>
            <tbody id="itemData">


            </tbody>
        </table>
    </div>
    <hr>
    <div id="pagination" class="row justify-content-center">

    </div>

    @include('admin.item.centerstockjs')
@endsection
@section('js')
    <script>
        var data = [];
        var selData = [];
        const countStep = {{ env('countstep', 24) }};
        var step = 0;
        var keyword = '';
        var $count = '';
        var total = 0;
        var lock = false;

        function loadData(loadData) {
            if (!lock) {
                lock = true;
                showProgress("Loading Item");
                axios.post('{{ route('admin.item.items-center-stock') }}', {
                        center_id: $('#center_id').val()
                    })
                    .then((res) => {

                        console.log(res.data);
                        data = res.data;
                        filterData();
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

        function show(_step) {
            step = _step;
            console.log(step);
            render(selData.slice(step * countStep, (step * countStep) + countStep));
            renderPagination(selData.length);
        }
        //reset
        function reset() {
            $('#sid').val('');
            keyword = '';
            step = 0;
            loadData();
        }

        function filterData() {
            step=0;
            selData = [];
            key = $('#sid')[0].value.toLowerCase().trim();
            datas = data.filter(o => o.t.toLowerCase().trim().startsWith(key));
            console.log(key,datas);
            const good = document.getElementById('good').checked;
            const warning = document.getElementById('warning').checked;
            const error = document.getElementById('error').checked;
            const nostock = document.getElementById('nostock').checked;
            for (let index = 0; index < datas.length; index++) {
                const _data = datas[index];
                clear = false;
                stock_type = 0;
                if (_data.c == null) {
                    stock_type = -2;
                } else {
                    const stock = JSON.parse(_data.c);

                    if (stock.a < 0) {
                        stock_type = -1;
                    } else {
                        if (stock.a < _data.m) {
                            stock_type = 0;
                        } else {
                            stock_type = 1;
                        }
                    }
                }

                switch (stock_type) {
                    case -2:
                        if (nostock) {
                            selData.push(_data);
                        }
                        break;
                    case -1:
                        if (error) {
                            selData.push(_data);
                        }
                        break;
                    case 0:
                        if (warning) {
                            selData.push(_data);
                        }
                        break;
                    case 1:
                        if (good) {
                            selData.push(_data);
                        }
                        break;
                    default:
                        break;
                }


            }
            render(selData.slice(step * countStep, (step * countStep) + countStep));
            renderPagination(selData.length);
        }

        function render(datas) {
            console.log('datas', datas, datas.length);
            html = '';
            const good = document.getElementById('good').checked;
            const warning = document.getElementById('warning').checked;
            const error = document.getElementById('error').checked;
            _r = $('#single-js').html();
            _r = _r.replaceAll('xxx_tr', 'tr');
            _r = _r.replaceAll('xxx_td', 'td');
            // console.log()
            for (let index = 0; index < datas.length; index++) {
                const _data = datas[index];

                rendered = _r.replaceAll('xxx_id', _data.id);
                rendered = rendered.replaceAll('xxx_title', _data.t);
                if (_data.c == null) {
                    rendered = rendered.replaceAll('xxx_wholesale', "--");
                    rendered = rendered.replaceAll('xxx_sell_price', "--");
                    rendered = rendered.replaceAll('xxx_stock', "--");
                    html += "<tr class='bg-danger text-white'>" + rendered + "</tr>";
                } else {
                    const stock = JSON.parse(_data.c);
                    rendered = rendered.replaceAll('xxx_wholesale', stock.w);
                    rendered = rendered.replaceAll('xxx_sell_price', stock.r);
                    rendered = rendered.replaceAll('xxx_stock', stock.a);
                    if (stock.a < 0) {
                        html += "<tr class='bg-danger text-white'>" + rendered + "</tr>";

                    } else {
                        if (stock.a < _data.m) {
                            html += "<tr class='bg-warning text-white'>" + rendered + "</tr>";

                        } else {
                            html += "<tr>" + rendered + "</tr>";

                        }
                    }
                }
            }
            $('#itemData').html('');
            $('#itemData').append(html);
            // console.log(html);

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
                if (total > 16) {
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

                    $('#pagination').append(
                        '<button class="btn btn-outline-primary mx-1 " onclick="show(0)">First</button>');
                    $('#pagination').append('<button class="btn btn-outline-primary mx-1 " onclick="show(' + (total - 1) +
                        ')">Last</button></div>');



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

        function search(ele) {
            step = 0;
            key = ele.value.toLowerCase();
            selData = data.filter(o => o.t.toLowerCase().startsWith(key));
            render(selData.slice(step * countStep, (step * countStep) + countStep));
            renderPagination(selData.length);
        }

        $(document).ready(function() {});
    </script>
@endsection
