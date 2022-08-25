<style>
    .d-print-show{
        display:none !important;
    }

</style>
<div class="row">

    <div class="col-md-12 mt-3">
        <div style="border: 1px solid rgb(136, 126, 126); padding:.5rem;">
            <strong>Ledger</strong> <span class="btn btn-success" onclick="printDiv('ledger');">Print</span>
            <hr>

            <div id="ledger" style="overflow: scroll;">
                <div class="d-print-show">
                    <style>
                        @media print {
                            td{
                                font-size: 1.2rem !important;
                                font-weight: 600 !important;
                            }


                        }
                        td,th{
                            border:1px solid black !important;
                            padding:2px !important;
                            font-weight: 600 !important;
                        }

                        table{
                            width:100%;
                            border-collapse: collapse;
                        }
                        thead {display: table-header-group;}
                        tfoot {display: table-header-group;}
                        .d-show-rate{

                            @if(env('showdisrate',0)==1)
                                display:inline;
                            @else
                                display:none !important;
                            @endif
                        }
                    </style>
                    <h2 style="text-align: center;margin-bottom:0px;font-weight:800;font-size:2rem;">
                        {{env('APP_NAME','Dairy')}} <br>

                    </h2>

                    <div style="font-weight:800;text-align:center;">
                        <span class="mx-3">  Ledger For : {{$user->name}} , </span>
                        {!!$title!!}
                    </div>
                </div>
                <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                    <tr>
                        <th>Date</th>
                        <th>Particular</th>
                        <th>Cr. (Rs.)</th>
                        <th>Dr. (Rs.)</th>
                        <th>Balance (Rs.)</th>
                    </tr>

                    @foreach ($ledgers as $l)
                        <tr data-id="ledger{{$l->id}}">
                            <td style="min-width:90px;">{{ _nepalidate($l->date) }}</td>
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

                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

