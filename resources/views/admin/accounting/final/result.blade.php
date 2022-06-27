@extends('admin.layouts.app')
@section('css')
<style>
    .w-40{
        width: 80%;
    }
    .w-10{
        width: 20%;
    }
    tr.main{
        font-weight: 600;
    }
    tr.sub>td:first-child{
        padding-left: 15px;
    }

    .table-bordered{
        margin-bottom: 0 !important;

    }
</style>
@endsection
@section('content')
    <div class="shadow mb-3">
        <div class="p-3">
            <form action="{{route('admin.accounting.final')}}" id="loadFinalAcc">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <label for="type">Statement Duration</label>
                        <select name="type" id="type" class="form-control ms">
                            <option value="1" selected>Whole Fiscal Year</option>
                            <option value="2">Quaterly</option>
                            <option value="3">Monthly</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="fy">FiscalYear</label>
                        <select name="fy" id="fy" class="form-control ms">

                        </select>
                    </div>
                    <div class="col-md-3 type type-3">
                        <label for="month">Month</label>
                        <select name="month" id="month" class="form-control ms">

                        </select>
                    </div>
                    <div class="col-md-3 type type-2">
                        <label for="quater">Quater</label>
                        <select name="quater" id="quater" class="form-control ms">

                        </select>
                    </div>
                    <div class="col-12 pt-2">
                        <button class="btn btn-primary">Load Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="shadow mb-3">
        <div class="p-3">
            <div id="alldata">

            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        const today=year*10000+month*100+day;
        const quaters=[
            {
                index:1,
                name: 'First',
                months:[4,5,6]
            },
            {
                index:2,
                name: 'Second',
                months:[7,8,9]
            },
            {
                index:3,
                name: 'Third',
                months:[10,11,12]
            },
            {
                index:4,
                name: 'Fourth',
                months:[1,2,3]
            }
        ];
        var startIndex=0;
        const fys={!! json_encode($fys) !!}
        var localMonths=months.map(o=>{return {name:o,index:++startIndex};});
        for (let index = 0; index < 3; index++) {
            localMonths.push(localMonths.shift());
        }


        $('#type').change(function (e) {
            e.preventDefault();
            e.stopPropagation();
            $('.type').hide();
            $('.type-'+this.value).show();
        });
        $(document).ready(function () {
            $('.type').hide();

            $('#month').html(localMonths.map(o=>{
                if(o.index==month){
                    return `<option selected value="${o.index}">${o.name}</option>`;

                }else{

                    return `<option value="${o.index}">${o.name}</option>`;
                }
            }));

            $('#fy').html(fys.map(o=>{
                const selected= today>=o.startdate && today<=o.enddate;

                if(selected){
                    console.log(o.startdate,today,o.enddate);
                    return `<option selected value="${o.id}">${o.name}</option>`

                }else{

                    return `<option value="${o.id}">${o.name}</option>`
                }
            }));
            $('#quater').html(quaters.map(o=>{

                if(o.months.includes(month)){
                    return `<option selected value="${o.index}">${o.name}</option>`
                }else{

                    return `<option value="${o.index}">${o.name}</option>`
                }
            }));

            $('#loadFinalAcc').submit(function (e) {
                e.preventDefault();
                axios.post(this.action,new FormData(this))
                .then((res)=>{
                    console.log(res.data);

                    $('#alldata').html( res.data);
                })
                .catch((err)=>{console.log(err);})
            });
        });

    </script>
@endsection
