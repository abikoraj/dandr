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
                @if ($farmer->cc > 0 || $farmer->tc > 0)
                    <th>
                        Milk Total
                    </th>
                    @if ($farmer->usetc || $farmer->use_ts_amount)
                        <th>
                            TS
                        </th>
                    @endif
                    @if ($farmer->usecc)
                        <th>
                            Cooling Cost
                        </th>
                    @endif
                    @if ($farmer->use_protsahan)
                        <th>
                            Protsahan <br> Amount
                        </th>
                    @endif
                    <th>
                        Total
                    </th>
                @else
                    <th>
                        Total
                    </th>
                @endif
                @if (env('hasextra', 0) == 1)
                    <th>Bonus</th>
                @endif
                <th>Purchase</th>
                <th>Payment</th>
                <th>Advance</th>
                <th>
                    Prev Balance
                </th>
                <th>
                    Prev Due
                </th>
                <th>
                    Paid
                </th>
                <th>Net Total</th>
                <th>Due Balance</th>
                <th>

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
                @if ($farmer->cc > 0 || $farmer->tc > 0)
                    <th>
                        {{ $farmer->total }}
                    </th>
                    @if ($farmer->usetc || $farmer->use_ts_amount)
                        <th>
                            {{ $farmer->tc }}
                        </th>
                    @endif
                    @if ($farmer->usecc)
                        <th>
                            {{ $farmer->cc }}
                        </th>
                    @endif
                    @if ($farmer->use_protsahan)
                        <th>
                            {{$farmer->protsahan_amount}}
                        </th>
                    @endif
                    <th>
                        {{ $farmer->grandtotal }}
                    </th>
                @else
                    <th>
                        {{ $farmer->grandtotal }}

                    </th>
                @endif
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
                <td>
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
                            <input type="hidden" name="fpaid" value=" {{ $farmer->fpaid }}">
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
                            <form id="updateSession" action="" method="post" onsubmit="return updateSession(event,this)">
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
                                <input type="hidden" name="close" id="close" value="1" checked>
                                <input type="text" name="date" id="closedate"
                                    value="{{ _nepalidate($closingDate) }}" readonly required>
                                <div class="py-1" style="white-space: nowrap;">
                                    <input type="checkbox" checked name="no_passbook" id="no_passbook" class="mr-2">
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
