@extends('admin.layouts.app')
@section('title','Dashboard')
@section('content')
    <div class="p-4">
       @foreach ($datas as $data)
            <div class="p-2 card shadow my-4">
                <h1 style="color:white;background:{{$data->wrong?"red":"green"}};padding:10px 0px;">
                    {{$data->user->name}}
                </h1>
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable" >
                    <tr>
                        <th>Date</th>
                        <th>Particular</th>
                        <th>Cr. (Rs.)</th>
                        <th>Dr. (Rs.)</th>
                        <th>Balance (Rs.)</th>
                        <th>first</th>
                        <th>track</th>
                    </tr>

                    @foreach ($data->ledgers as $l)
                        <tr data-id="ledger{{$l->id}}" style="color:white;background:{{$l->wrong?"red":"green"}}">
                            <td>{{ _nepalidate($l->date) }}</td>
                            <td>{!! $l->title !!}</td>

                            <td>
                                @if ($l->type==1)
                                    {{ rupee((float)$l->amount) }}
                                @endif
                            </td>
                            <td>
                                @if($l->type==2)
                                {{ rupee((float)$l->amount) }}
                                @endif
                            </td>
                            <td>
                                {{ (($l->dr == null)|| ($l->dr<=0))?"":"Dr. ".rupee((float)$l->dr) }}
                                {{(($l->cr == null)|| ($l->cr<=0))?"":"Cr. ".rupee((float)$l->cr )}}
                            </td>
                            <td>

                                {{$l->first}}
                            </td>
                            <td>
                                {{$l->track}}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
       @endforeach
    </div>
@endsection
