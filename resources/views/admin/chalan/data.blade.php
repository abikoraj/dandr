<div>
    <table class="table-table-bordered mb-3">
        <tr>
            <th>Date</th>
            <th>Items</th>
            <th>Total</th>
            <th>Action</th>
        </tr>
        @foreach ($datas as $data)
            <tr>
                <td>{{ _nepalidate($data->date) }} </td>
                <td>
                    @php
                        $tot = 0;
                    @endphp
                    @foreach ($chalanItems->where('employee_chalan_id', $data->id) as $item)
                        @php
                            $tot += $item->rate*$item->qty;
                        @endphp
                        {{ $item->title }},
                    @endforeach
                </td>
                <td>Rs.{{ $tot }}</td>
                <td>
                    @if ($data->approved)
                        @if ($data->closed)
                            @if (auth_has_per('15.03'))
                                <a href="{{ route('admin.chalan.chalan.final.details',$data->id) }}" class="btn btn-primary btn-sm">View Detail</a>
                            @endif
                        @else
                            @if (auth_has_per('15.03'))

                                <a href="{{ route('admin.chalan.chalan.details',$data->id) }}" class="btn btn-primary btn-sm">Manage</a>
                            @endif
                            @if (auth_has_per('15.04'))
                                <a href="{{ route('admin.chalan.closing.index',$data->id) }}" class="btn btn-primary btn-sm">Close</a>
                            @endif
                            @if (auth_has_per('15.09') && canDeleteChalan($data->id))
                                <a href="{{ route('admin.chalan.manage.cancel',$data->id) }}" class="btn btn-danger btn-sm">Cancel</a>
                            @endif
                            <a href="{{ route('admin.chalan.manage.print',$data->id) }}" class="btn btn-success btn-sm">Print</a>

                        @endif
                    @else
                        @if (auth_has_per('15.05'))
                            <a href="{{ route('admin.chalan.manage.approved',$data->id) }}" class="btn btn-primary btn-sm">Approve</a>
                        @endif
                        @if (auth_has_per('15.08'))
                            <a href="{{ route('admin.chalan.manage.edit',$data->id) }}" class="btn btn-success btn-sm">Edit</a>
                        @endif
                        @if (auth_has_per('15.09') && canDeleteChalan($data->id))
                            <a href="{{ route('admin.chalan.manage.cancel',$data->id) }}" class="btn btn-danger btn-sm">Cancel</a>
                        @endif
                        <a href="{{ route('admin.chalan.manage.print',$data->id) }}" class="btn btn-success btn-sm">Print</a>
                    
                    @endif


                </td>
            </tr>

        @endforeach
    </table>
</div>
