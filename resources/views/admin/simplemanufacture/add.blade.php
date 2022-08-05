@extends('admin.layouts.app')
@section('title', 'Manufacture Items - Add')
@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/select2.css') }}">
    <style>
        .step-btn {
            cursor: pointer;
            padding: 10px 15px;
            flex: 1;
            text-align: center;
        }

        .step-btn.active {
            background: rgb(0, 122, 204);
            color: white;
        }

        .step-div {
            display: none;

        }

        .step-div.active {
            display: block;
        }
    </style>
@endsection
@section('head-title')
    <a href="{{ route('admin.simple.manufacture.add') }}">Manufacture Items</a> / Add
@endsection
@section('toobar')
@endsection
@section('content')

    <div class="d-flex shadow">
        <div class="steps step-btn  step-1 active" onclick="CurrentStep=1;refresh();">
            Raw Materials
        </div>
        <div class="steps step-btn  step-2" onclick="CurrentStep=2;refresh();">
            Produced Items
        </div>
        <div class="steps step-btn  step-3" onclick="CurrentStep=3;refresh();">
            Wastage Materials
        </div>
    </div>
    <div class="p-2  shadow">
        <div class="row mt-3">
            <div class="col-12">
                <div class="w-25">

                </div>
            </div>
            <div class="col-md-4">
                <label for="item_id">Item</label>
                <select name="item_id" id="item_id" class="ms form-control">

                </select>
            </div>
            <div class="col-md-3">
                <label for="center_id">Centers</label>
                <select name="center_id" id="center_id" class="ms form-control">

                </select>
            </div>
            <div class="col-md-2">
                <label for="amount">Amount</label>
                <input name="amount" id="amount" class="form-control" step="0.0001">
            </div>
            <div class="col-md-3  d-flex align-items-end">
                <button class="btn btn-primary w-100" onclick="AddData()">
                    Add <br>
                    <span id="add-type">

                    </span>
                </button>
            </div>
        </div>
        <hr>

        <div>
            <div class="steps step-div step-1 active">
                <table>
                    th
                </table>
            </div>
            <div class="steps step-div step-2">
                step2
            </div>
            <div class="steps step-div step-3">
                step3
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script>
        var data = {
            rawMaterials: [],
            wastage: [],
            items: [],
            push: function(localdata) {
                switch (CurrentStep) {
                    case 1:
                        data.rawMaterials.push(localdata);
                        break;
                    case 2:
                        data.items.push(localdata);
                        break;
                    case 3:
                        data.wastage.push(localdata);
                        break;

                    default:
                        break;
                }
            }
        }
        var CurrentStep = 1;
        var uid = 1;
        const items = {!! json_encode($items) !!};
        const centers = {!! json_encode($centers) !!};
        const maincenter = {{ env('maincenter', -1) }};
        console.log(items, centers);
        steps = ['', 'Raw Materials', 'Produced Items', 'Wastage Materials'];

        function refresh() {
            $('.steps').removeClass('active');
            $('.step-' + CurrentStep).addClass('active');
            $('#add-type').html(steps[CurrentStep]);
        }

        $(document).ready(function() {
            refresh();
            $('#item_id').html("<option></option>" + (items.map(i => `<option value="${i.id}">${i.title}</option>`)
                .join('')));
            $('#item_id').select2();

            $('#center_id').html((centers.map(c => {
                if (c.id == maincenter) {
                    return `<option value="${c.id}" selected>${c.name}</option>`;

                } else {

                    return `<option value="${c.id}">${c.name}</option>`;
                }
            }).join('')));
        });

        function saveData(){
            if(data.items.length==0){
                alert('Please Enter Produced Item');
                return;
            }
            axios.post("{{route('admin.simple.manufacture.add')}}",data)
            .then((res)=>{
                window.location.reload();
                showNotification("bg-success","Manufacture Added Successfully");
            })
            .catch((err)=>{
                if(err.response){
                    showNotification("bg-danger",err.response.data.message)

                }else{

                    showNotification("bg-danger","Some Error Occured");
                }
            })
        }

        function AddData() {
            const amount = parseFloat($('#amount').val());
            const item_id = parseInt($('#item_id').val());
            const center_id = parseInt($('#center_id').val());
            let canAdd=true;
            if (isNaN(item_id)) {
                alert('Please Enter Amount');
                canAdd=false;
            }
            if (isNaN(amount)) {
                alert('Please Enter Amount');
                canAdd=false;

            }
            if(canAdd){
                const localdata = {
                    item: items.find(o => o.id == item_id),
                    center: centers.find(o => o.id == center_id),
                    amount: amount,
                    uid:uid++
                };
                
                console.log(localdata);
                data.push(localdata);
            }

        }
    </script>
@endsection
