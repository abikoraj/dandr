<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('pos/css/main.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <title> @yield('title')</title>
</head>

<body>
    <div id="main">
        @include('pos.layout.header')
        <div id="panel">
            @yield('content')

        </div>
    </div>
    <a href="" id="xxx_52" class="d-none" target="_blank">click</a>


    @include('admin.layouts.working')
    <script>
        const beaconURL = '{{ route('pos.counterStatus') }}';
        const itemsURL = '{{ route('pos.items') }}';
        const customerSearchURL = '{{ route('pos.customers-search') }}';
        const addCustomer = '{{ route('pos.customers-add') }}';
        const addBillURL = '{{ route('pos.billing.add') }}';
        const printBillURL = '{{ route('pos.billing.print',['bill'=>'__xx__']) }}';
        const printedBillURL = '{{ route('pos.billing.printed') }}';
        const cardnoRequired={{env('cardnoRequired',0)}};
        const savePayment={{env('savePayment',0)}};
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('backend/js/axios.js') }}"></script>
    <script src="{{ asset('backend/js/signalr.js') }}"></script>
    <script src="http://localhost:4200/signalr/hubs"></script>

    <script src="{{ asset('pos/js/names.js') }}"></script>
    <script src="{{ asset('pos/js/funcstions.js') }}"></script>
    <script src="{{ asset('pos/js/main.js') }}"></script>
    {{-- <script>
        $(function() {
            $.connection.hub.url = "http://localhost:4200/signalr";

            // Declare a proxy to reference the hub.
            var chat = $.connection.myHub;
            chat.client.addMessage = function(name, message) {
                console.log(name, message);
            }; 
            $.connection.hub.start().done(function() {
              console.log('signalr started');
              chat.server.send('asd', 'adsd0');
            });
        });
    </script> --}}
    @yield('js')
    @yield('js1')
    @yield('js2')
    @yield('js3')
    @yield('js4')
</body>

</html>
