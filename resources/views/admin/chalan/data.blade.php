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
                    @if ($data->closed)
                    <a href="{{ route('admin.chalan.chalan.final.details',$data->id) }}" class="btn btn-primary btn-sm">View Detail</a>

                    @else
                    <a href="{{ route('admin.chalan.chalan.details',$data->id) }}" class="btn btn-primary btn-sm">Manage</a>
                    <a href="{{ route('admin.chalan.closing.index',$data->id) }}" class="btn btn-primary btn-sm">Close</a>
                    @endif
                </td>
            </tr>

        @endforeach
    </table>
</div>
