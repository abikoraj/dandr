<hr>
@php
    $types=['',"Raw Material Used","Produced Items",'Wastage']
@endphp

<div class="d-flex shadow-local ">
    <div class="steps step-btn  step-1 active" onclick="CurrentStep=1;refresh();">
        Summary
    </div>
    <div class="steps step-btn  step-2" onclick="CurrentStep=2;refresh();">
        Processes
    </div>
</div>


<div class="shadow-local mt-3">
    <div class="steps-data step-data-1 active" >
        @for ($i = 1; $i <= 3; $i++)
        <div class="p-2 shadow mt-2">
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
                </tr>
                @foreach ($items->where('type',$i) as $item)
                    <tr>
                        <td>
                            {{$item->title}}
                        </td>
                        <td>
                            {{$item->amount}}
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>  
        @endfor
    </div>
    <div class="steps-data  step-data-2 p-2">
        <table class="table table-bordered">
            <tr>
                <th>
                    #REF ID
                </th>
                <th>
                    Date
                </th>
                <th>
                    Title
                </th>
                <th>

                </th>
            </tr>
            @foreach ($processes as $process)
                <tr>
                    <th>
                        {{$process->id}}
                    </th>
                    <td>
                        {{_nepalidate($process->date)}}
                    </td>
                    <td>
                        {{$process->title}}
                    </td>
                    <td>
                        <a class="btn btn-primary" href="{{route('admin.simple.manufacture.detail',['process'=>$process->id])}}">
                            Detail
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>

