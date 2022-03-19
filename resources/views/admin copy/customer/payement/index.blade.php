@extends('admin.layouts.app')
@section('title', 'Customer Payment')
@section('head-title', 'Customer Payment')

@section('content')
    <div class="row">
        <div class="col-md-3">

            <input type="text" id="sid" placeholder="Search Customer" class="form-control mb-2">
            <table class="table table-bordered  dataTable" style="cursor: pointer" id="customers">
                @if($large)
                @foreach ($customers as $customer)
                    <tbody id="data">
                        <tr data-name="{{ $customer->user->name }}" id="customer_{{ $customer->user_id }}" onclick="selectCustomer({{ $customer->user_id }},'{{ $customer->user->name }}')"> 
                            <td>
                                {{ $customer->user->name }}
                            </td>
                        </tr>
                    </tbody>
                @endforeach
                @endif
            </table>
        </div>
        <div class="col-md-9" id="allData">

        </div>
    </div>




@endsection
@section('js')
    @if($large)
        @include('admin.search.list')
        
    @endif
    <script>
        @if(!$large)
            initTableSearch('sid', 'data', ['name']);
        @endif
        lock = false;
        var _id=-1;
        var _name="";
        function selectCustomer(id,name){
            _id=id;
            _name=name;
            showProgress("Loading "+name+"'s Data")
            axios.post('{{route('admin.customer.payment.index')}}',{"id":id})
            .then((res)=>{
                $('#allData').html(res.data);
                hideProgress();
                setDate('date',true);
            })
            .catch((err)=>{
                hideProgress();

            });
        }

        function addPayment(e){
            e.preventDefault();
            if(!lock){
                lock=true;
                showProgress("Adding Payment for "+_name);
                data=new FormData(document.getElementById('addPayment'));
                axios.post("{{route('admin.customer.payment.add')}}",data)
                .then((res)=>{
                    $('#allData').html(res.data);
                    hideProgress();
                    setDate('date',true);
                })
                .catch((err)=>{
                    hideProgress();

                });
            }
        }

        @if($large)
            function loadCustomer(){
                axios.get('{{route('admin.customer.all')}}')
                .then((res)=>{
                    $('#sid').search({
                        filterfunc:'filterCustomer',
                        renderfunc:'renderCustomer',
                        rendercustom: true,
                        renderele: "#customers",
                        list:res.data,
                        renderfirst:true
                    });
                });
            }
            function filterCustomer(_keyword) {
                console.log(this,_keyword);
                let _list=[];
                let _index=0;
                for (let index = 0; index < this.length; index++) {
                    const element = this[index];

                    if (element.name.toLowerCase().startsWith(_keyword.toLowerCase())) {
                        _list.push(element);
                        if (_index >= 100) {
                            break;
                        }
                        _index += 1;
                    }
                }
                return _list;
            }
            function  renderCustomer() {
            html="";
            console.log(this);
            this.forEach((item) => {

                html +='<tr data-name="'+item.name+'->name }}" id="customer_'+item.id+'" onclick="selectCustomer('+item.user_id+',\''+item.name+'\')"><td>'+item.name+'</td></tr>';

            });
            
            return html; 
            }
            loadCustomer();
        @endif
    </script>
@endsection
