@extends('admin.layouts.app')
@section('title','Dashboard')
@section('content')
    <div class="p-4">
        <table class="table">
            <tr>
                <th>
                    name
                </th>
                <th>
                    balance
                </th>
                <th>
                    balancetype
                </th>
                <th>
                    cr
                </th>
                <th>
                    dr
                </th>
                <th>
                    date
                </th>


            </tr>
            @foreach ($datas as $data)
                <tr style="color:white;background:{{$data->ok?'green':'red'}};">
                    <td>
                        {{$data->no}}
                    </td>
                    <td>
                        <a href="{{route('test-all',['id'=>$data->id])}}"> {{$data->name}}</a>
                    </td>
                    <td>
                        {{$data->amount}} {{$data->type==1?"CR":"DR"}}
                    </td>
                    <td>
                        {{$data->cr}}
                    </td>
                    <td>
                        {{$data->dr}}
                    </td>
                    <td>
                        {{$data->date}}
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
