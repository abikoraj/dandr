@extends('sahakari.layouts.app')
@section('title', 'Member - Add')
@section('head-title')
<a href="{{ route('sahakari.members.index') }}">Members</a> / Add
@endsection
@section('css')
<style>
    .form-control{
        height: auto !important;
        padding: 5px 10px !important;
    }
    .row{
        margin:0px -7px;
    }
    .col-md-3{
        padding:0px 7px;
    }
    .col-md-2{
        padding:0px 7px;
    }
    .col-md-6{
        padding:0px 7px;
    }
    .col-md-9{
        padding:0px 7px;
    }
    .form-group{
        margin-bottom:5px;
    }
    /* .col-md-3 {
        margin-bottom: 1px;
    } */
    .col-md-4 {
        padding:0px 7px;
       
    } 
    .col-md-8 {
        padding:0px 7px;
       
    }

    label {
        font-weight: 700;
        margin-bottom: 2px !important;
    }

    .title {
        margin: 0 !important;
        font-weight: 900;
        font-size: 16px;
    }

    .center-fg {
        display: flex;
        align-items: center;
    }

    .p-r {
        position: relative;
    }

    .btn-close {
        position: absolute;
        top: 0;
        right: 15px;
        height: 30px;
        width: 30px;
        background: red;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    hr{
        margin:5px 0px;
    }

    .save-holder{
        position: fixed;
        bottom: 20px;
        right: calc(50% - 300px);
        width: 600px;
        background: white
    }
</style>
<link rel="stylesheet" href="{{ asset('backend/css/dropify-multiple.min.css') }}">
@endsection
@section('content')
<form id="member-add" action="{{ route('sahakari.members.add') }}" method="post" enctype="multipart/form-data"
    onsubmit="return save(event);">
    @csrf
    @include('partials.adressdatalist')
    @include('sahakari.member.snippets.basic')
    @include('sahakari.member.snippets.allac')
    {{-- @include('sahakari.member.snippets.member') --}}
    @include('sahakari.member.snippets.address')
    @include('sahakari.member.snippets.nominee')
    {{-- @include('sahakari.member.snippets.farmerdetail') --}}
    {{-- @include('sahakari.member.snippets.distributer') --}}
    {{-- @include('sahakari.member.snippets.employee') --}}
    @include('sahakari.member.snippets.document')
    <div class="card my-3 px-3 py-1 shadow save-holder">
        <div class="row justify-content-center">
            <div class="col-md-3 pr-md-0 ">
                <button class="btn btn-primary w-100">Save Member</button>
            </div>
            <div class="col-md-3">
                <span class="btn btn-danger w-100" onclick="reset()">Reset Data</span>
            </div>
        </div>
    </div>
</form>

@endsection
@section('js')
<script src="{{ asset('backend/plugins/dropify/js/dropify.min.js') }}"></script>
<script src="{{ asset('backend/js/dropify-multiple.js') }}"></script>
<script>

    function resetForm() {
        document.getElementById('member-add').reset();
        $('.toogle').each(function() {
            on = $(this).data('on');
            collapse = $(this).data('collapse');
            if (on) {
                $(collapse).removeClass('d-none');
                $(this).children('.on').removeClass('d-none');
                $(this).children('.off').addClass('d-none');
            } else {
                $(collapse).addClass('d-none');
                $(this).children('.on').addClass('d-none');
                $(this).children('.off').removeClass('d-none');

            }
        });
        $('.switch').each(function() {
            target = this.dataset.switch;
            if (this.checked) {
                $(target).removeClass('d-none');
            } else {
                $(target).addClass('d-none');
            }
        });
        $('#documents').empty();
    }

    function reset() {
        if (confirm('All Data will be Cleared.Do you want to reset Form?')) {
            resetForm();
        }
    }

    function save(e) {
        e.preventDefault();
        console.log(e);
        showProgress('Adding Member');
        axios.post('{{ route('sahakari.members.add') }}', new FormData(e.srcElement))
            .then((res) => {
                console.log(res);
                hideProgress();
                toastr.success(res.data, '{{env('APP_NAME')}}');

            })
            .catch((err) => {
                console.log(err);
                hideProgress();
                for (let index = 0; index < err.response.data.length; index++) {
                    const element = err.response.data[index];
                    toastr.error(element, '{{env('APP_NAME')}}');
                    
                }

            });
    }


    setFocusShortCut([
        'f1','name',
        'f2','center_id',
        'f3','credit_limit',
        'f4','salary',
        'f5','member_no',

    ]);

    setClickShortCut([
        "alt+n",'acc_type_normal',
        "alt+d",'acc_type_dependent',
        "alt+f",'is_farmer',
        "alt+s",'is_supplier',
        "alt+i",'is_distributer',
        "alt+e",'is_emp',
        "alt+c",'is_customer',
    ])
</script>
@endsection
