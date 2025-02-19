<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Token System</title>
    <link rel="stylesheet" href="{{asset('cropper/cropper.min.css')}}">
    <link rel="stylesheet" href="{{asset('bootstrap/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('sweetalert/sweetalert2.min.css')}}">

</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header text-white text-center bg-success fs-4">
            <span id="form-title">Login</span>
        </div>

        <!-- Login Form -->
        <form action="{{ route('loggingin') }}" method="POST" id="login-form">
            @csrf
            <div class="card-body">
                <div class="form-group mb-3">
                    <input type="text" class="form-control" name="username" id="username" placeholder="username" value="{{ old('username') }}" required>
                </div>
                <div class="form-group mb-3">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                    <!-- Toggle show password -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="show-password" />
                        <label class="form-check-label" for="show-password">Show Password</label>
                    </div>
                </div>

            </div>
            <div class="card-footer d-flex justify-content-between">

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe" />
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>

                <button class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
</div>
<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('cropper/cropper.min.js')}}"></script>
<script src="{{asset('bootstrap/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('sweetalert/sweetalert2.min.js')}}"></script>
<script>
    $(document).ready(function () {


        // Toggle password visibility for login form
        $('#show-password').change(function() {
            var passwordField = $('#password');
            if ($(this).prop('checked')) {
                passwordField.attr('type', 'text');
            } else {
                passwordField.attr('type', 'password');
            }
        });

    });
</script>
</body>
</html>
