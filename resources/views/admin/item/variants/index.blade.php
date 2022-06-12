@extends('admin.layouts.app')
@section('title', 'Items')
@section('head-title')
<a href="{{route('admin.item.index')}}">Items</a> / {{$item->name}} / Variants
@endsection

@section('toobar')
@if (auth_has_per('03.01'))
    <button type="button" class="btn btn-primary waves-effect m-r-20" onclick="win.showGet('add New Varaint','{{route('admin.item.variants.add',['id'=>$item->id])}}')" >Create
        New Variant</button>
@endif
@endsection
@section('content')
@foreach ($variants as $variant)
        @include('admin.item.variants.single',['variant'=>$variant])
@endforeach
@endsection
@section('js')
        <script>
            function save(ele,e){
                e.preventDefault();
                axios.post(ele.action,new FormData(ele))
                .then((res)=>{
                    showNotification('bg-success','Variant added sucessfully.');
                    window.location.reload();
                })
                .catch((err)=>{
                    showNotification('bg-danger','Variant Cannot be added. Please try again.');
                })

            }
        </script>
@endsection
