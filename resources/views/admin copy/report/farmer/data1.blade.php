<style>
    td,th{
        border:1px solid black;
    }
    table{
        width:100%;
        border-collapse: collapse;
    }
    thead {display: table-header-group;}
    tfoot {display: table-header-group;}
</style>
<h2 style="text-align: center;margin-bottom:0px;font-weight:800;font-size:2rem;">
    {{env('APP_NAME','Dairy')}}
</h2>

<div style="display: flex;justify-content: space-between;font-weight:800;">
    <span>
        Year : {{$year}}

    </span>
    <span>
        Month : {{$month}}
    </span>
    <span>
        Session : {{$session}}
    </span>
    <span>
        Center : {{$center->name}}
    </span>
</div>
<form action="{{route('report.farmer.session')}}" method="POST">
    <input type="hidden" name="year" value="{{$year}}" >
    <input type="hidden" name="month" value="{{$month}}" >
    <input type="hidden" name="session" value="{{$session}}" >
    <input type="hidden" name="center_id" value="{{$center->id}}" >
    @php
        $i=1;
        $d=0;
        $start=true;
        $point=false;

        $milktotal=0;
        $bonustotal=0;
        $totaltotal=0;
        $duetotal=0;
        $advancetotal=0;
        $prevduetotal=0;
        $nettotaltotal=0;
        $balancetotal=0;
    @endphp


    @csrf
    @foreach ($data as $farmer)
    @if ($start)

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Milk (l)</th>
                <th>Snf%</th>
                <th>Fat%</th>
                <th>Price/l</th>
                @if ($usecc || $usetc)
                    <th>
                        MilK Total
                    </th>
                    <th>
                        TS
                    </th>
                    <th>
                        Cooling Cost
                    </th>
                @endif
                <th>Total</th>
                @if (env('hasextra',0)==1)
                    <th>
                        Bonus ( {{ round($center->bonus,2) }} % )
                    </th>
                @endif
                <th>Due</th>
                <th>Avance</th>
                <th>
                    Prev Due
                </th>
                <th>Net Total</th>
                <th>Due Balance</th>

                    <th>Signature</th>

            </tr>
        </thead>
        <tbody>
            @php
                $start=false;
                $d+=1;
            @endphp
    @endif


                <tr>
                    <td>
                        {{$farmer->no}}

                        @if ($farmer->old==false)
                            <input type="hidden" name="farmers[]" value="{{$farmer->toJson()}}" >
                        @endif
                    </td>
                    @php
                        $t='farmer-'.$farmer->id;
                    @endphp

                    <td>
                        {{$farmer->name}}
                    </td>
                    <td>
                        {{($farmer->milk)}}
                        @php
                            $milktotal+=$farmer->milk;
                        @endphp
                        {{-- <input type="hidden" name="milk[{{$t}}]" value="{{($farmer->m_milk+$farmer->e_milk)}}" > --}}

                    </td>
                    <td>
                        {{$farmer->snf}}
                        {{-- <input type="hidden" name="snf[{{$t}}]" value="{{($farmer->snf)}}" > --}}

                    </td>
                    <td>
                        {{$farmer->fat}}
                        {{-- <input type="hidden" name="fat[{{$t}}]" value="{{($farmer->fat)}}" > --}}

                    </td>
                    <td>
                        {{$farmer->rate}}
                        {{-- <input type="hidden" name="rate[{{$t}}]" value="{{($farmer->rate)}}" > --}}

                    </td>
                    @if ($usecc || $usetc)
                        <th>
                            {{$farmer->total}}
                        </th>
                        <th>
                            {{$farmer->tc}}
                        </th>
                        <th>
                            {{$farmer->cc}}
                        </th>
                    @endif
                    <td>
                        {{$farmer->grandtotal}}
                        @php
                            $totaltotal+=$farmer->grandtotal;
                        @endphp
                        {{-- <input type="hidden" name="total[{{$t}}]" value="{{($farmer->total)}}" > --}}

                    </td>
                    @if(env('hasextra',0)==1)
                        <td>
                             {{ $farmer->bonus??0}}
                             @php
                                $bonustotal+=$farmer->bonus;
                            @endphp
                        </td>
                    @endif
                    <td>
                        {{$farmer->due}}
                        {{-- <input type="hidden" name="due[{{$t}}]" value="{{($farmer->due)}}" > --}}
                        @php
                            $duetotal+=$farmer->due;
                        @endphp
                    </td>
                    <td>
                        {{$farmer->advance}}
                        {{-- <input type="hidden" name="advance[{{$t}}]" value="{{($farmer->advance)}}" > --}}
                        @php
                            $advancetotal+=$farmer->advance;
                        @endphp
                    </td>
                    <td>
                        {{$farmer->prevdue}}
                        {{-- <input type="hidden" name="prevdue[{{$t}}]" value="{{($farmer->prevdue)}}" > --}}
                        @php
                            $prevduetotal+=$farmer->prevdue;
                        @endphp
                    </td>

                    <td>
                        {{$farmer->nettotal}}
                        {{-- <input type="hidden" name="nettotal[{{$t}}]" value=" {{$tt>0?$tt:0}}" > --}}
                        @php
                            $nettotaltotal+=$farmer->nettotal;
                        @endphp
                    </td>
                    <td>
                        {{$farmer->balance}}
                        @php
                            $balancetotal+=$farmer->balance;
                        @endphp
                        {{-- <input type="hidden" name="balance[{{$t}}]" value=" {{$tt<0?(-1*$tt):0}}" > --}}
                    </td>

                    <td>
                        @if ($farmer->old)
                           Already Taken
                        @endif
                    </td>

                </tr>
            @php
                $i+=1;
                $pb=31;
                if($d==1){
                    $pb=env('firstpage',31);
                }else{
                    $pb=env('secondpage',34);
                }
                if($i==$pb){
                    $point=true;
                    $start=true;
                    $i=1;

                }
            @endphp
        @if( $point)
                <tr>
                    <td colspan="2">Total</td>
                    <td>{{$milktotal}}</td>
                    <td>--</td>
                    <td>--</td>
                    <td>--</td>
                    <td>{{$totaltotal}}</td>
                    @if(env('hasextra',0)==1)
                        <td>{{$bonustotal}}</td>
                    @endif
                    <td>
                        {{$duetotal}}
                    </td>
                    <td>
                        {{$advancetotal}}
                    </td>
                    <td>
                        {{$prevduetotal}}
                    </td>
                    <td>
                        {{$nettotaltotal}}
                    </td>
                    <td>
                        {{$balancetotal}}
                    </td>
                    <td></td>
                </tr>
            @php
                $point=false;
                $milktotal=0;
                $bonustotal=0;
                    $totaltotal=0;
                    $duetotal=0;
                    $advancetotal=0;
                    $prevduetotal=0;
                    $nettotaltotal=0;
                    $balancetotal=0;
            @endphp
            </tbody>
        </table>
        <div class="fs"></div>
        @endif

            @endforeach
        @if ($i<31)
        <tr>
            <td colspan="2">Total</td>
            <td>{{$milktotal}}</td>
            <td>--</td>
            <td>--</td>
            <td>--</td>
            <td>{{$totaltotal}}</td>
            @if(env('hasextra',0)==1)
                <td>{{$bonustotal}}</td>
            @endif
            <td>
                {{$duetotal}}
            </td>
            <td>
                {{$advancetotal}}
            </td>
            <td>
                {{$prevduetotal}}
            </td>
            <td>
                {{$nettotaltotal}}
            </td>
            <td>
                {{$balancetotal}}
            </td>
            <td></td>
        </tr>
            </tbody>
        </table>
        @endif
    <div class="py-2 d-print-none">
        <input type="submit" value="Update Session Data" class="btn btn-success">
    </div>
</form>



