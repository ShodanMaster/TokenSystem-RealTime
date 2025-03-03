<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('bootstrap/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('sweetalert/sweetalert2.min.css')}}">
    <link rel="stylesheet" href="{{asset('datatable/dataTables.dataTables.min.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    @vite('resources/js/app.js')

</head>
<body>
    <nav class="navbar navbar-expand-lg bg-success">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="{{route('admin.index')}}">Token System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.issue') ? 'active' : '' }} text-white" aria-current="page" href="{{route('admin.issue')}}">Issue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('admin.counter') ? 'active' : '' }} text-white" aria-current="page" href="{{route('admin.counter')}}">Counter</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          Reports
                        </a>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" href="{{route('admin.tokenreport')}}">Token Report</a></li>
                          <li><a class="dropdown-item" href="{{route('admin.counterreport')}}">Counter Report</a></li>
                        </ul>
                    </li>
                </ul>
                    <a href="{{route('admin.loggingout')}}"><button class="btn btn-danger" type="button">Logout</button></a>
            </div>
        </div>
    </nav>
    <div class="container content-container mt-5">
        @yield('content')
    </div>
    <script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('datatable/dataTables.min.js')}}"></script>
    <script src="{{asset('bootstrap/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('sweetalert/sweetalert2.min.js')}}"></script>
    @yield('scripts')
</body>
</html>
