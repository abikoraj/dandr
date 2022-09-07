@extends('admin.layouts.app')
@section('title')
@endsection
@section('css')
   
@endsection
@section('head-title')
<a href="{{route('admin.simple.manufacture.index')}}">
    Manufacture Items 
</a>
/ {{$process->title}} MainBatch:- #{{$process->id}}
@endsection

@section('content')
    <div class="shadow-local p-3 mb-3">
        <div class="row">
            <div class="col-md-4">
                <strong>
                    Manufacturing Date
                </strong>
                <div>
                    {{_nepalidate($process->date)}}
                </div>
            </div>
            <div class="col-md-4">
                <strong>
                    Manufacturing Date
                </strong>
                <div>
                    {{_nepalidate($process->date)}}
                </div>
            </div>
        </div>
    </div>
    @php
        $types=['',"Raw Material Used","Produced Items",'Wastage']
    @endphp
    <div >
        @for ($i = 1; $i <= 3; $i++)
        <div class="p-2 shadow-local mb-3">
            <h5>
                {{$types[$i]}}
            </h5>
            <table class="table-bordered table">
                <tr>
                
                    <th>
                        Item
                    </th>
                    <th>
                        Amount
                    </th>
                    <th>
                        Center
                    </th>
                    @if ($i==2)
                        
                    <th>
                        BatchID
                    </th>
                    <th>
                        Expiry Date
                    </th>
                    
                    @endif
                </tr>
                @foreach ($items->where('type',$i) as $item)
                    <tr>
                        <td>
                            {{$item->title}}
                        </td>
                        <td>
                            {{$item->amount}}
                        </td>
                        <td>

                        </td>
                        @if ($i==2)
                        <td>
                            #{{$process->id}}.{{$item->id}}
                        </td>
                        <td>
                            {{$item->expiry==null?'--':_nepalidate($item->expiry)}}
                        </td>
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>  
        @endfor
    </div>
  
@endsection
@section('js')
   
@endsection
