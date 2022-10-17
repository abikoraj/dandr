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
                <th class="d-print-none">

                </th>
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
                <td class="d-print-none">
                    @if ($farmer->old == false)
                        <form action="{{ route('admin.farmer.passbook.close') }}" method="POST"
                            onsubmit="return closeSession(event,this);">
                            @csrf
                            <input type="hidden" name="year" value="{{ $farmer->session[0] }}">
                            <input type="hidden" name="month" value="{{ $farmer->session[1] }}">
                            <input type="hidden" name="session" value="{{ $farmer->session[2] }}">
                            <input type="hidden" name="id" value="{{ $farmer->id }}">
                            <input type="hidden" name="center_id" value="{{ $farmer->center_id }}">
                            <input type="hidden" name="snf" value="{{ $farmer->snfavg }}">
                            <input type="hidden" name="fat" value="{{ $farmer->fatavg }}">
                            <input type="hidden" name="rate" value=" {{ $farmer->milkrate }}">
                            <input type="hidden" name="milk" value="{{ $farmer->milkamount }}">
                            <input type="hidden" name="total" value=" {{ $farmer->total }}">
                            <input type="hidden" name="grandtotal" value=" {{ $farmer->grandtotal }}">
                            <input type="hidden" name="cc" value=" {{ $farmer->cc }}">
                            <input type="hidden" name="tc" value=" {{ $farmer->tc }}">
                            <input type="hidden" name="due" value=" {{ $farmer->purchase }}">
                            <input type="hidden" name="bonus" value=" {{ $farmer->bonus }}">
                            <input type="hidden" name="advance" value=" {{ $farmer->advance }}">
                            <input type="hidden" name="prevdue" value=" {{ $farmer->prevdue }}">
                            <input type="hidden" name="nettotal" value=" {{ $farmer->nettotal }}">
                            <input type="hidden" name="balance" value=" {{ $farmer->balance }}">
                            <input type="hidden" name="prevbalance" value=" {{ $farmer->prevbalance }}">
                            <input type="hidden" name="paidamount" value=" {{ $farmer->paidamount }}">
                            <input type="hidden" name="fpaid" value="{{ $farmer->fpaid }}">
                            <input type="hidden" name="protsahan_amount" value="{{ $farmer->protsahan_amount }}">
                            <input type="hidden" name="transport_amount" value="{{ $farmer->transport_amount }}">

                            <input type="hidden" name="close" id="close" value="1" checked>

                            <label for=>Session Close Date</label>
                            <input type="text" name="date" id="closedate" value="{{ _nepalidate($closingDate) }}"
                                readonly required>
                            <div class="py-1">

                                <input type="checkbox" name="no_passbook" id="no_passbook" class="mr-2">
                                <label for="no_passbook">
                                    No Passbook
                                </label>
                            </div>
                            <button class="btn btn-sm btn-success w-100">Close Session</button>
                        </form>
                    @else
                        @if ($farmer->report->has_passbook)
                            Session Closed
                        @else
                            <form id="updateSession" action="" method="post"
                                onsubmit="return updateSession(event,this)">
                                @csrf

                                <input type="hidden" name="id" value="{{ $farmer->id }}">
                                <input type="hidden" name="report_id" value="{{ $farmer->report->id }}">
                                <input type="hidden" name="snf" value="{{ $farmer->snfavg }}">
                                <input type="hidden" name="fat" value="{{ $farmer->fatavg }}">
                                <input type="hidden" name="rate" value=" {{ $farmer->milkrate }}">
                                <input type="hidden" name="milk" value="{{ $farmer->milkamount }}">
                                <input type="hidden" name="total" value=" {{ $farmer->total }}">
                                <input type="hidden" name="grandtotal" value=" {{ $farmer->grandtotal }}">
                                <input type="hidden" name="cc" value=" {{ $farmer->cc }}">
                                <input type="hidden" name="tc" value=" {{ $farmer->tc }}">
                                <input type="hidden" name="due" value=" {{ $farmer->purchase }}">
                                <input type="hidden" name="bonus" value=" {{ $farmer->bonus }}">
                                <input type="hidden" name="advance" value=" {{ $farmer->advance }}">
                                <input type="hidden" name="prevdue" value=" {{ $farmer->prevdue }}">
                                <input type="hidden" name="nettotal" value=" {{ $farmer->nettotal }}">
                                <input type="hidden" name="balance" value=" {{ $farmer->balance }}">
                                <input type="hidden" name="prevbalance" value=" {{ $farmer->prevbalance }}">
                                <input type="hidden" name="paidamount" value=" {{ $farmer->paidamount }}">
                                <input type="hidden" name="fpaid" value=" {{ $farmer->fpaid }}">
                                <input type="hidden" name="protsahan_amount"
                                    value="{{ $farmer->protsahan_amount }}">
                                <input type="hidden" name="transport_amount"
                                    value="{{ $farmer->transport_amount }}">
                                <input type="hidden" name="close" id="close" value="1" checked>
                                <input type="text" name="date" id="closedate"
                                    value="{{ _nepalidate($closingDate) }}" readonly required>
                                <div class="py-1" style="white-space: nowrap;">
                                    <input type="checkbox" checked name="no_passbook" id="no_passbook"
                                        class="mr-2">
                                    <label for="no_passbook">
                                        No Passbook
                                    </label>
                                </div>
                                <button class="btn btn-success w-100">
                                    Update Data
                                </button>
                            </form>
                        @endif
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>
