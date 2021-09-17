@extends('admin.layouts.app')
@section('title', 'Items')
@section('head-title', 'Items')

@section('toobar')
    <button type="button" class="btn btn-primary waves-effect m-r-20" data-toggle="modal" data-target="#largeModal">Create
        New Item</button>
@endsection
@section('content')
    <div class="pt-2 pb-2">
        <div class="row">
            <div class="col-md-3">
                <input type="search" id="sid" placeholder="Search" class="form-control" oninput="keyword=this.value">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" onclick="step=0;loadData();">Load Data</button>
            </div>
            <div class="col-md-2 ">
                <button class="btn btn-danger w-100" onclick="reset()">Reset</button>
            </div>
        </div>
    </div>
    <div class="table-responsive">

        <table id="newstable1" class="table table-bordered table-striped table-hover js-basic-example dataTable">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Item Number</th>
                    <th>sell Price </th>
                    <th>Stock </th>
                    <th>Unit Type</th>
                    <th>Reward (%)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="itemData">

                @foreach ($items as $item)
                    @include('admin.item.single',['item'=>$item])
                @endforeach
            </tbody>
        </table>
    </div>
    <hr>
    <div id="pagination" class="row justify-content-center">
        clas
    </div>
    <div id="result">
        lorem
    </div>
    @include('admin.item.add')
    @include('admin.item.singlejs')
    {{-- @include('admin.item.edit') --}}
@endsection
@section('js')
    <script>
        countStep = {{ env('countstep', 24) }};
        @if ($large)
            step=0;
            keyword='';
            $count='';
            total=0;
        
        @else
            initTableSearch('sid', 'itemData', ['name']);
        @endif

        lock = false;
        @if($large)

        function loadData(loadData) {
            if (!lock) {
                lock = true;
                showProgress("Loading Item");
                axios.post('{{ route('admin.item.index') }}', {
                        "step": step,
                        "keyword": keyword != '' ? keyword : null
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
        @endif

        
        function saveData(e) {
            e.preventDefault();
            if (!lock) {
                lock = true;
                var bodyFormData = new FormData(document.getElementById('add-bill'));
                axios({
                        method: 'post',
                        url: '{{ route('admin.item.save') }}',
                        data: bodyFormData,
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(function(response) {
                        console.log(response);
                        showNotification('bg-success', 'Item added successfully!');
                        $('#largeModal').modal('toggle');
                        $('#form_validation').trigger("reset")
                        // $('#itemData').prepend(response.data);
                        lock = false;
                    })
                    .catch(function(response) {
                        showNotification('bg-danger', 'Item Number already exist!');
                        //handle error
                        console.log(response);
                        lock = false;

                    });
            }
        }

        function initEdit(id) {
            win.showPost("Edit Item", "{{ route('admin.item.edit') }}", {
                "id": id
            });
        }

        function editData(e) {

            e.preventDefault();
            if (!lock) {
                lock = true;
                var trid = $('#eid').val();
                var dataBody = new FormData(document.getElementById('editform'));
                axios({
                        method: 'post',
                        url: '{{ route('admin.item.update') }}',
                        data: dataBody,
                    })
                    .then(function(response) {
                        showNotification('bg-success', 'Item updated successfully!');
                        win.hide();
                        $('#item-' + trid).replaceWith(response.data);
                        lock = false;

                    })
                    .catch(function(response) {
                        console.log(response);
                        lock = false;

                    });
            }
        }

        // delete item
        function removeData(id) {
            if (confirm('Are you sure?')) {
                axios({
                        method: 'get',
                        url: '/admin/item-delete/' + id,
                    })
                    .then(function(response) {
                        showNotification('bg-danger', 'Item deleted successfully!');
                        $('#item-' + id).remove();
                    })
                    .catch(function(response) {
                        showNotification('bg-danger', 'You do not have authority to delete!');

                        console.log(response)
                    })
            }
        }

        @if($large)
        //reset 
        function  reset() {
            $('#sid').val('');
            keyword='';
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
                rendered = rendered.replaceAll('xxx_title', data.title);
                rendered = rendered.replaceAll('xxx_number', data.number);
                rendered = rendered.replaceAll('xxx_sell_price', data.sell_price);
                rendered = rendered.replaceAll('xxx_stock', data.stock);
                rendered = rendered.replaceAll('xxx_unit', data.unit);
                rendered = rendered.replaceAll('xxx_reward_percentage', data.reward_percentage);
                html += rendered;
                console.log(rendered);
            }
            $('#itemData').html('');
            $('#itemData').append(html);
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
        $('#sid').keydown(function (e) { 
            if(e.which==13 && keyword.length>2){
                loadData();
            }
        });

        loadData();
        @endif
        // render=render.replaceAll('xxx_tr','tr');
    </script>
@endsection
