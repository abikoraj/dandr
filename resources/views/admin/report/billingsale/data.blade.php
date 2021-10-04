@include('admin.report.billingsale.summary')

<div class="p-2 mt-3 shadow">

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="home-tab" data-toggle="tab" href="#sales-1" role="tab" aria-controls="home" aria-selected="true">Sales</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="profile-tab" data-toggle="tab" href="#sales-return-1" role="tab" aria-controls="profile" aria-selected="false">Sales Return</a>
        </li>
    </ul>
    <div class="tab-content" id="_myTabContent p-2">
        <div class="tab-pane fade show active shadow" id="sales-1" role="tabpanel" aria-labelledby="home-tab">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="home-tab" data-toggle="tab" href="#data-1" role="tab" aria-controls="home" aria-selected="true">Bills Wise</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab" data-toggle="tab" href="#data-2" role="tab" aria-controls="profile" aria-selected="false">Item Wise</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="data-1" role="tabpanel" aria-labelledby="home-tab">
                    <table class="table table-bordered mt-2">
                        <thead>
                            <tr>
                                <th>Bill No</th>
                                <th>Date</th>
                                <th>Customer </th>
                                <th>Total</th>
                                <th>Discount</th>
                                <th>Taxable</th>
                                <th>Tax</th>
                                <th>Gross Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th>Return</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bills as $k=>$b)
                            <tr>
                                <td>{{ $b->bill_no }}</td>
                                <td>{{ _nepalidate($b->date) }}</td>
                                <td>{{ $b->customer_name }}</td>
                                <td>{{ (float) $b->total }}</td>
                                <td>{{ (float) $b->discount }}</td>
                                <td>{{ (float) $b->taxable }}</td>
                                <td>{{ (float) $b->tax }}</td>
                                <td>{{ (float) $b->grandtotal }}</td>
                                <td>{{ (float) $b->paid }}</td>
                                <td>{{ (float) $b->due }}</td>
                                <td>{{ (float) $b->return }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="tab-pane fade" id="data-2" role="data-2" aria-labelledby="profile-tab">
                    <table class="table table-bordered mt-2">
                        <thead>
                                <th>Name</th>
                                <th>Qty</th>
                                <th>Rate</th>
                                <th>Amount</th>
                                <th>Discount</th>
                                <th>Taxable</th>
                                <th>Tax</th>
                                <th>Total</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($billItemDatas as $_i)
                                @php
                                    $i=(object)$_i;
                                @endphp
                                @if (count($i->value)==1)
                                    @php
                                        $i_qty=(object)($i->value[0]);
                                    @endphp
                                    <tr>
                                        <td>{{ $i->item_name }}</td>
                                        <td>
                                            {{$i_qty->qty}}
                                        </td>

                                        <td>{{ $i_qty->rate }}</td>
                                        <td>{{ $i_qty->amount }}</td>
                                        <td>{{ $i_qty->discount }}</td>
                                        <td>{{ $i_qty->taxable }}</td>
                                        <td>{{ $i_qty->tax }}</td>
                                        <td>{{ $i_qty->total }}</td>
                                    </tr>
                                @else
                                <tr>
                                    <td>{{$i->item_name}}</td>
                                    <td colspan="7"></td>
                                </tr>
                                @foreach ($i->value as $_i_qty)
                                @php
                                    $i_qty=(object)$_i_qty;
                                @endphp
                                <tr>
                                    <td>-</td>
                                    <td>
                                        {{$i_qty->qty}}
                                    </td>

                                    <td>{{ $i_qty->rate }}</td>
                                    <td>{{ $i_qty->amount }}</td>
                                    <td>{{ $i_qty->discount }}</td>
                                    <td>{{ $i_qty->taxable }}</td>
                                    <td>{{ $i_qty->tax }}</td>
                                    <td>{{ $i_qty->total }}</td>
                                </tr>
                                @endforeach
                                @endif

                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <div class="tab-pane fade shadow " id="sales-return-1" role="tabpanel" aria-labelledby="home-tab">

        </div>

    </div>


</div>
