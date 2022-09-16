<!doctype html>
<html class="no-js " lang="en">


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="description" content="Dairy Management System">

    <title>Admin :: Sign In</title>
    <!-- Favicon-->
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style.min.css') }}">
</head>

<body class="theme-blush">

    <div class="authentication">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-sm-12">
                    <form class="card auth_form" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="header">
                            <img class="logo" src="{{ asset('backend/images/logo.svg') }}" alt="">
                            <h5>Log in</h5>
                        </div>

                        <div class="body">
                            @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <div class="input-group mb-3">
                                <input type="number" class="form-control" name="phone" placeholder="Username">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="zmdi zmdi-account-circle"></i></span>
                                </div>
                            </div>
                            <div class="input-group mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Password">
                                <div class="input-group-append">
                                    <span class="input-group-text"><a href="forgot-password.html" class="forgot" title="Forgot Password"><i class="zmdi zmdi-lock"></i></a></span>
                                </div>
                            </div>
                            <div class="checkbox">
                                <input id="remember_me" type="checkbox">
                                <label for="remember_me">Remember Me</label>
                            </div>
                            <button class="btn btn-primary btn-block waves-effect waves-light">SIGN IN</button>
                        </div>
                    </form>
                    <div class="copyright text-center">
                        &copy;
                        <script>
                            document.write(new Date().getFullYear())
                        </script>,
                        <span><a href="#">{{env('tag','')}}</a></span>
                    </div>
                </div>
                <div class="col-lg-8 col-sm-12">
                    <div class="card">
                        <img src="{{ asset('backend/images/signin.svg') }}" alt="Sign In" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery Core Js -->
    <script src="{{ asset('backend/bundles/libscripts.bundle.js') }}"></script>
    <script src="{{ asset('backend/bundles/vendorscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js -->
</body>


</html>
