<div class="col-md-12">

    <hr>
    <h5 class="font-weight-bold">
        Session Summary
    </h5>
    <hr>

    <div class="report p-2">
        <table class="table">
            <tr>
                <th>
                    Total Milk
                </th>
                <th>
                    Fat
                </th>
                <th>
                    Snf
                </th>
                <th>
                    Rate
                </th>
                @if ($farmer->cc > 0 || $farmer->tc > 0 || $farmer->protsahan_amount > 0 || $farmer->transport_amount)

                    <th>
                        Milk Total
                    </th>
                    @if (env('farmer_detail_milk_detail', 0) == 1)
                        @if ($farmer->tc > 0)
                            <th>
                                TS
                            </th>
                        @endif
                        @if ($farmer->cc > 0)
                            <th>
                                Cooling <br> Cost
                            </th>
                        @endif
                        @if ($farmer->protsahan_amount > 0)
                            <th>
                                Protsahan <br> Amount
                            </th>
                        @endif
                        @if ($farmer->transport_amount > 0)
                            <th>
                                Transport <br> Amount
                            </th>
                        @endif
                    @else
                        <th>
                            Added Amount
                        </th>
                    @endif
                @endif

                <th>
                    Total
                </th>
                @if (env('hasextra', 0) == 1)
                    <th>Bonus</th>
                @endif
                <th>Purchase</th>
                <th>Payment</th>
                <th>Advance</th>
                <th>
                    Prev <br> Balance
                </th>
                <th>
                    Prev <br> Due
                </th>
                <th>
                    Milk <br>
                    Payment
                </th>
                <th>Net <br> Total</th>
                <th>Due <br> Balance</th>
               
            </tr>
            <tr>
                <td>
                    {{ $farmer->milkamount }}
                </td>
                <td>
                    {{ $farmer->fatavg }}
                </td>
                <td>
                    {{ $farmer->snfavg }}
                </td>
                <td>

                    {{ $farmer->milkrate }}


                </td>
                @if ($farmer->cc > 0 || $farmer->tc > 0 || $farmer->protsahan_amount > 0 || $farmer->transport_amount)
                    <th>
                        {{ $farmer->total }}
                    </th>
                    @if (env('farmer_detail_milk_detail', 0) == 1)
                        @if ($farmer->tc > 0)
                            <th>
                                {{ $farmer->tc }}
                            </th>
                        @endif
                        @if ($farmer->cc > 0)
                            <th>
                                {{ $farmer->cc }}
                            </th>
                        @endif
                        @if ($farmer->protsahan_amount > 0)
                            <th>
                                {{ $farmer->protsahan_amount }}
                            </th>
                        @endif
                        @if ($farmer->transport_amount > 0)
                            <th>
                                {{ $farmer->transport_amount }}
                            </th>
                        @endif
                    @else
                    <th>
                        {{ $farmer->tc + $farmer->cc + $farmer->protsahan_amount + $farmer->transport_amount }}
                    </th>
                    @endif
                @endif
                <th>
                    {{ $farmer->grandtotal }}

                </th>
                @if (env('hasextra', 0) == 1)
                    <td> {{ $farmer->bonus }} </td>
                @endif
                <td>
                    {{ $farmer->purchase }}
                </td>
                <td>
                    {{ $farmer->fpaid }}
                </td>
                <td>
                    {{ $farmer->advance }}
                </td>
                <td>
                    {{ $farmer->prevbalance }}
                </td>
                <td>
                    {{ $farmer->prevdue }}
                </td>
                <td>{{ $farmer->paidamount }}</td>

                <td>
                    {{ $farmer->nettotal }}
                </td>
                <td>
                    {{ $farmer->balance }}
                </td>
                
            </tr>
        </table>
    </div>
</div>