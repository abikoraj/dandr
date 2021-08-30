<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('pos/css/main.css')}}">
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


   @include('admin.layouts.working')
    <script>
      const beaconURL='{{route('pos.counterStatus')}}';
      const itemsURL='{{route('pos.items')}}';
      const customerSearchURL='{{route('pos.customers-search')}}';
      const addCustomer='{{route('pos.customers-add')}}';
      const addBillURL='{{route('pos.billing.add')}}';
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('backend/js/axios.js') }}"></script>

    <script src="{{asset('pos/js/names.js')}}"></script>
    <script src="{{asset('pos/js/funcstions.js')}}"></script>
    <script src="{{asset('pos/js/main.js')}}"></script>
  </body>
</html>