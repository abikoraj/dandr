@extends('admin.layouts.app')
@section('title', 'Manufacture Processes')
@section('head-title', 'Manufacture Processes')
@section('toobar')
    @if (auth_has_per('13.03'))
        <a href="{{route('admin.manufacture.process.add')}}" class="btn btn-primary">Add New Process</a>
    @endif
@endsection
@section('content')
    @php
    $stages = ['', 'Pending', 'Processing', 'Finished'];
    @endphp
    <div class="row">
        @for ($i = 1; $i < 4; $i++)
            <div class="col-md-4 text-center">
                <button class="btn btn-toogle btn-toogle-{{ $i }}" data-stage="{{ $i }}">
                    {{ $stages[$i] }}
                </button>
            </div>
        @endfor
    </div>
    <br>
    <table class="table">
        <tr>
            <th>
                Name
            </th>
            <th>
                Expected Yield
            </th>
            <th>
                Process Start
            </th>
            <th>
                Process Expected End
            </th>
            <th>

            </th>
            <tbody id="processes">

            </tbody>
        </tr>
    </table>
@endsection
@section('js')
    <script>
        const processes = {!! json_encode($processes) !!};
        const processDetailURL='{{route('admin.manufacture.process.detail',['id'=>'xxx_id'])}}';
        var current = 1;
        $(document).ready(function() {
            $('.btn-toogle').click(function() {
                current = this.dataset.stage;
                setCurrent();
            });
            setCurrent();
        });

        function MoveToProcessing(){

        }
        function loadCurrent(){
            const currentDate=new Date();
            $('#processes').html(processes.filter(o=>o.stage==current).map(o=>renderElement(o)).join(''));
        }

        function renderElement(process){
            return "<tr>"+
                "<td>"+process.title+ "</td>"+
                "<td>"+process.expected+ "("+process.unit+")</td>"+
                "<td>"+process.start+ "</td>"+
                "<td>"+process.expected_end+ "</td>"+
                @if(auth_has_per('13.04'))
                "<td><a href='"+processDetailURL.replace('xxx_id',process.id)+"'>Detail</a></td>"+
                @endif
                "</tr>"
        }

        function setCurrent(id) {
            $('.btn-toogle').removeClass('btn-primary');
            $('.btn-toogle-' + current).addClass('btn-primary');
            loadCurrent();
        }
    </script>
@endsection
