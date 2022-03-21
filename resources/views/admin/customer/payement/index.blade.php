@extends('admin.layouts.app')
@section('title', 'Customer Payment')
@section('head-title', 'Customer Payment')

@section('content')
    <div class="row">
        <div class="col-md-4">

            <input type="text" onkeydown="keyboardSel(event)" id="sid" placeholder="Search Customer" oninput="filterCustomer(this.value)" class="form-control mb-2">
            <div style="max-height: 430px;overflow-y:auto;">

                <table class="table table-bordered " style="cursor: pointer" id="customers">

                </table>
            </div>
        </div>
        <div class="col-md-8" id="allData">

        </div>
    </div>




@endsection
@section('js')



    <script>
        var allData = [];
        var customers = [];
        var lock = false;
        var _id = -1;
        var _name = "";
        total=0;
        index=0;

        function keyboardSel(e) {

            if(e.which==13){
                $($('#customers>tr')[index]).click();
                return;
            }else if(e.which==38){
                index-=1;
                if(index<0){
                    index=0;
                }
            }else if(e.which==40){
                index+=1;
                if(index>total){
                    index=total;
                }
            }
            $('#customers>tr').removeClass('btn-primary');
            $($('#customers>tr')[index]).addClass('btn-primary');
            $('#customers>tr')[index].scrollIntoView();
        }

        function selectCustomer(id, name,ele) {
            $('#customers>tr').removeClass('btn-primary');
            $(ele).addClass('btn-primary');
            _id = id;
            _name = name;
            showProgress("Loading " + name + "'s Data")
            axios.post('{{ route('admin.customer.payment.index') }}', {
                    "id": id
                })
                .then((res) => {
                    $('#allData').html(res.data);
                    hideProgress();
                    setDate('date', true);
                })
                .catch((err) => {
                    hideProgress();

                });
        }

        function addPayment(e) {
            e.preventDefault();
            if (!lock) {
                lock = true;
                showProgress("Adding Payment for " + _name);
                data = new FormData(document.getElementById('addPayment'));
                axios.post("{{ route('admin.customer.payment.add') }}", data)
                    .then((res) => {
                        $('#allData').html(res.data);
                        hideProgress();
                        setDate('date', true);
                    })
                    .catch((err) => {
                        hideProgress();

                    });
            }
        }


        function loadCustomer() {
            axios.get('{{ route('admin.customer.all') }}')
                .then((res) => {
                    allData = res.data;
                    customers = allData;
                   renderCustomer();
                    console.log(res.data);

                });
        }

        function filterCustomer(_keyword) {
            if(_keyword.length>0){
                customers=allData.filter(o=>o.name.toLowerCase().startsWith(_keyword)||o.phone.startsWith(_keyword));
                renderCustomer();
            }else{
                customers=allData;

            }

        }

        function renderCustomer() {
            html = "";

            let id=0;
            customers.forEach((item) => {

                html += '<tr  index="'+(id++)+'"  onclick="selectCustomer(' + item.user_id + ',\'' + item.name + '\',this)"><td>' + item.name +
                    ' ('+item.phone+')</td></tr>';

            });
            total=id;
            $('#customers').html( html);
            index=0;
            $($('#customers>tr')[index]).addClass('btn-primary');
            $('#customers>tr')[index].scrollIntoView();
        }
        loadCustomer();
    </script>
@endsection
0
