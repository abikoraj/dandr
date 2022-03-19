<style>
    .d-print-show{
          display:none !important;
      }
  
  </style>
  <div class="row">
     
  
      <div class="col-md-12 mt-3">
          <div style="border: 1px solid rgb(136, 126, 126); padding:1rem;">
              <strong>Ledger</strong> <span class="btn btn-success" onclick="printDiv('ledger');">Print</span>
              <hr>
  
              <div id="ledger">
                  <div class="d-print-show">
                      <style>
                          @media print {
                              td{
                                  font-size: 1.2rem !important;
                                  font-weight: 600 !important;
                              }
  
  
                              th:last-child, td:last-child {
                                  display: none;
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
                  <table class="table table-bordered table-striped table-hover js-basic-example dataTable" >
                      <tr>
                          <th>Date</th>
                          <th>Particular</th>
                          <th>Cr. (Rs.)</th>
                          <th>Dr. (Rs.)</th>
                          <th>Balance (Rs.)</th>
                          <th></th>
                      </tr>
                      @if ($prev>0)
                          
                          <tr>
                              <td>
                                  --
                              </td>
                              <td>
                                  Previous Balance
                              </td>
                              @if ($prev>0)
  
                              <td>
  
                              </td>
                              <td>
                              {{$prev}}
                              </td>
                                  <td>
                                      Dr.{{$prev}}
                                  </td>
                                  @elseif ($prev<0)
                                  <td>
                                      {{-1*$prev}}
                                  </td>
                                  <td>
  
                                  </td>
                                  <td>
                                      Cr.{{-1*$prev}}
                                  </td>
                                  @else
                                  <td>
                                      --
                                  </td>
                                  <td>
                                      --
                                  </td>
                                  <td>
                                      --
                                  </td>
                              @endif
                              <td></td>
                          </tr>
                      @endif
                      @foreach ($arr as $l)
                          <tr>
                              <td>{{ _nepalidate($l->date) }}</td>
                              <td>{!! $l->title !!}</td>
  
                              <td>
                                  @if ($l->type==1)
                                      {{ (float)$l->amount }}
                                  @endif
                              </td>
                              <td>
                                  @if($l->type==2)
                                  {{ (float)$l->amount }}
                                  @endif
                              </td>
                              <td>
                                  @if ($l->amt>0)
  
                                      Dr. {{(float)$l->amt}}
                                  @elseif ($l->amt<0)
                                      Cr. {{(float)(-1*$l->amt)}}
                                  @else
                                      --
                                  @endif
                                </td>
                              <td class="d-print-none">
                                 @if ($l->identifire==125)
                                 <a class="btn btn-primary " target="_blank" href="{{route('admin.supplier.bill.item.detail',['bill'=>$l->foreign_key])}}">Bill Detail</a>
                                 <button onclick="cancelBill({{$l->foreign_key}});"  class="btn btn-danger">Cancel</button>
                                 @endif
                                 @if ($l->identifire==127 || $l->identifire==128)
                                    <button  onclick="initEditLedger('{{$l->title}}',{{$l->id}});">Edit</button>
                                    <button  onclick="deleteLedger({{$l->id}},loadData);">Delete</button>
                                 @endif

  
                              </td>
                          </tr>
                      @endforeach
                  </table>
              </div>
          </div>
      </div>
  </div>
    