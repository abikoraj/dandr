@extends('admin.layouts.app')
@section('title','Dashboard')
@section('content')
    <div class="p-4">
        <div class="py-4">
            <div class="row">

                @foreach ($user as $key=>$item)
                    <div class="col-md-4 card p-1 shadow-sm">

                        <strong class="">{{$key}}</strong>
                        <span class="pr-2">{{$item}}</span>
                    </div>
                @endforeach
            </div>
        </div>
        <table class="table">
            <tr>
                <th>
                    id
                </th>

                <th>
                    date
                </th>
                <th>title</th>
                <th>
                   amount
                </th>

                <th>
                    cr
                </th>
                <th>
                    dr
                </th>


            </tr>
            @foreach ($datas as $data)
                <tr >
                    <td>
                        {{$data['id']}}
                    </td>
                    <td>
                        {{_nepalidate($data['date'])}}
                    </td>
                    <td>
                        {{$data['title']}}
                    </td>
                    <td>
                        {{$data['amount']}}
                    </td>
                    <td>
                        {{$data['cr']}}
                    </td>
                    <td>
                        {{$data['dr']}}
                    </td>

                </tr>
            @endforeach
        </table>
    </div>
@endsection
