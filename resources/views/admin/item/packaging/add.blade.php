@extends('admin.layouts.app')
@section('title', 'Items - Packing')
@section('head-title')
<a href="{{route('admin.item.index')}}">Items</a> /
<a href="{{route('admin.item.packaging.index')}}">Packaging</a> /
Add

@endsection

@section('toobar')

@endsection
@section('content')

@endsection
@section('js')
    <script>
        const items={!! json_encode($items) !!};
        const units={!! json_encode($units) !!};
        console.log(items,units);
    </script>
@endsection
