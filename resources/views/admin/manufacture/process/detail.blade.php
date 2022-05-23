@extends('admin.layouts.app')
@section('title')
    Manufacture Process - Detail
@endsection
@section('css')
@endsection
@section('head-title')
    <a href="{{ route('admin.manufacture.process.index') }}">Manufacture Process</a> / {{ $process->title }} / Batch
    #{{ $process->item_id }}.{{ $process->id }}
@endsection
@section('toobar')
@endsection
@section('content')
    @php
    $btnStage = ['', 'warning', 'primary', 'success'];
    $textStage = ['', 'Pending', 'Processing', 'Finished'];
    @endphp
    <span class="px-3 py-2 d-inline-block bg-{{ $btnStage[$process->stage] }} text-white">
        Status: {{ $textStage[$process->stage] }}
    </span>
    <hr>
    <div class="row">

        <div class="col-md-2">
            <strong>Item</strong> <br>
            {{ $process->title }}
        </div>
        <div class="col-md-2">
            <strong>Expected Yield</strong> <br>
            {{ $process->expected }} {{ $process->unit }}
        </div>
        <div class="col-md-2">
            <strong>Actual Yield</strong> <br>
            {{ $process->stage == 3 ? $process->actual . ' ' . $process->unit : '--' }}
        </div>
        <div class="col-md-2">
            <strong>Start Time</strong> <br>
            {{ $process->start }}
        </div>
        @if ($process->stage < 3)
            <div class="col-md-2">
                <strong>Expected End Time</strong> <br>
                {{ $process->expected_end }}
                <hr>
                <strong id="remaining_expected_end ">

                </strong>
            </div>
        @else
            <div class="col-md-2">
                <strong>End Time</strong> <br>
                {{ $process->end }}
            </div>
        @endif
        @if ($multiStock)
            <div class="col-md-2">
                <strong>Center/Branch</strong> <br>
                {{ $process->center }}
            </div>
        @endif
    </div>
    <hr>
    <h4 class="m-0">Raw Material Used</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Item Name</th>
                <th>qty</th>
                @if ($multiStock)
                    <th>Center</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>
                        {{ $item->title }}
                        <div id="stock_check_{{ $item->id }}" class="stock_check text-danger">

                        </div>
                    </td>
                    <td>
                        {{ $item->amount }} {{ $item->unit }}
                    </td>
                    @if ($multiStock)
                        <td>{{ $item->center }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="shadow p-3">
        @if ($process->stage == 1)
            <form action="{{ route('admin.manufacture.process.start.process', ['id' => $process->id]) }}" method="post"
                id="start-process">
                @csrf
                <div class="row">
                    <div class="col-md-4">

                        <div class="form-group">
                            <label for="start">Start Datetime </label>
                            <input type="datetime-local" onchange="changeStart(this)" name="start" id="start"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="expected_end">Expected finish Datetime</label>
                            <input type="datetime-local" name="expected_end" id="expected_end" class="form-control"
                                required>
                        </div>
                    </div>
                    <div class="col-md-4 pt-4">
                        <button class="btn btn-primary "> Start Process</button>
                    </div>
                </div>

            </form>
        @elseif ($process->stage==2)
            <form action="{{ route('admin.manufacture.process.finish.process', ['id' => $process->id]) }}" method="post"
                id="start-process">
                @csrf
                <div class="row">
                    <div class="col-md-4">

                        <div class="form-group">
                            <label for="start">Actual Yield </label>
                            <input type="number" value="{{$process->expected}}" step="0.0001" onchange="changeStart(this)" name="actual" id="actual"
                                class="form-control" required>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end">Finish Time</label>
                            <input type="datetime-local"  name="end" id="end" class="form-control"
                                required>
                        </div>
                    </div>

                    <div class="col-md-4 pt-4">
                        <button class="btn btn-primary " onclick="return prompt('Enter yes to finish process')=='yes';"> Finish Process</button>
                    </div>
                </div>

            </form>
        @endif
    </div>
@endsection
@section('js')
    <script src="{{ asset('backend/plugins/select2/select2.min.js') }}"></script>
    <script>
        const currentDate = getDateTimeLocal(new Date('{{ $process->start }}'));
        const end = getDateTimeLocal(new Date('{{ $process->expected_end }}'));
        </script>
    @if ($process->stage == 1)
        <script>
            $(document).ready(function() {
                $('#start').val(currentDate);
                $('#expected_end').val(end);
                $('#start-process').submit(function(e) {
                    e.preventDefault();
                    checkStock(this)
                })
            });

            function startProcess(ele) {
                axios.post('{{ route('admin.manufacture.process.start.process', ['id' => $process->id]) }}', new FormData(ele))
                    .then((res) => {
                        hideProgress();
                        window.location.reload();
                    })
                    .catch((err) => {
                        showNotification('bg-danger', "Process cannot be started");
                        hideProgress();
                    });

            }

            function checkStock(ele) {
                showProgress('Starting Manufacturing Process for {{ $process->title }}');
                axios.get('{{ route('admin.manufacture.process.check.stock.saved', ['id' => $process->id]) }}')
                    .then((res) => {
                        data = res.data;
                        if (data.hasstock) {
                            startProcess(ele);
                        } else {
                            data.msgs.forEach(msg => {
                                $('#stock_check_' + msg.id).html('Not Enough Stock');
                            });
                            hideProgress();
                        }

                    })
                    .catch((err) => {
                        showNotification('bg-danger', "Process cannot be started");

                        hideProgress();

                    });
            }

            function changeStart(ele) {
                const currentDate = new Date($('#start').val());
                const expectedDate = new Date(currentDate.valueOf() + {{ $process->finish_ms }});
                $('#expected_end').val(getDateTimeLocal(expectedDate));
            }
        </script>
    @elseif($process->stage == 2)
        <script>
            const countDownDate=new Date('{{ $process->expected_end }}').getTime();


            $(document).ready(function () {
                const x = setInterval(function() {

                    // Get today's date and time
                    const now =new Date().getTime();

                    // Find the distance between now and the count down date
                    const distance = countDownDate - now;

                    // Time calculations for days, hours, minutes and seconds
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    // Display the result in the element with id="demo"
                    document.getElementById("remaining_expected_end").innerHTML = days + "d " + hours + "h " +
                        minutes + "m " + seconds + "s ";

                    // If the count down is finished, write some text
                    if (distance < 0) {
                        clearInterval(x);
                        document.getElementById("remaining_expected_end").innerHTML = "Process Ended";
                    }
                }, 1000);
                $('#end').val(end);
            });
        </script>
    @endif
@endsection
