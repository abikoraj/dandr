@extends('admin.layouts.app')
@section('title')
    Jinsi Milan
@endsection
@section('head-title')
    Jinsi Minlan
@endsection
@section('toobar')
    <a href="{{route('admin.jinsimilan.add')}}" class="btn btn-primary">Add New Milan</a>
@endsection
@section('content')
    <form action="{{route('admin.jinsimilan.index')}}" onsubmit="loadData(this,event);">
        @csrf
        @include('admin.layouts.daterange',[''])
        <div class="row">
            <div class="col-md-4">
                <button class="btn btn-primary">
                    Load Data
                </button>
            </div>
        </div>
    </form>
    <hr>
    <div >
        <table class="table table-borered">
            <thead>
                <tr>
                    <th>
                        #ID
                    </th>
                    <th>
                        Date
                    </th>
                    <th>
                        From
                    </th>
                    <th>
                        To
                    </th>
                    <th>
                        Amount
                    </th>
                    <th>

                    </th>
                </tr>
            </thead>
            <tbody id="alldata">
                
            </tbody>
        </table>
    </div>
@endsection
@section('js')
    <script>
        var jinsiMilans=[];
        var editURL="{{route('admin.jinsimilan.edit',['id'=>'xxx_id'])}}";
        $(document).ready(function () {
            $('#type').val(1).change();
        });

        function del(id){
            if(yes()){

                showProgress("Deleting Data");
                axios.post('{{route('admin.jinsimilan.del')}}',{id:id})
                .then((res)=>{
                    $('#jinsiMilan-'+id).remove();
                    successAlert('Jinsi Milan deleted successfully');
                })
                .catch((err)=>{
                    errAlert(err);
                })
            }
        }
        function loadData(ele,e) {
            e.preventDefault();
            showProgress("Loading Data");
            $('#alldata').html('');

            axios.post(ele.action,new FormData(ele))
            .then((res)=>{
                console.log(res.data);
                jinsiMilans=res.data;
                const html=jinsiMilans.map(o=>`<tr id="jinsiMilan-${o.id}">
                    <td>${o.id}</td>
                    <td>${toNepaliDate(o.date)}</td>
                    <td>${o.fromParty}</td>
                    <td>${o.toParty}</td>
                    <td>${o.amount}</td>
                    <td>
                        <a href="${editURL.replace('xxx_id',o.id)}">Edit </a> |
                        <span onclick="del(${o.id})"  class="text-danger"> Del </span> 
                    </td>
                    </tr>`);
                $('#alldata').html(html);
                hideProgress();
            })
            .catch((err)=>{
                errAlert(err);
            })
        }
    </script>

@endsection