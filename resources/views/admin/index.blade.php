@extends('admin.layout')

@section('content')

<h1 class="text-center my-4">Admin Dashboard</h1>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card text-dark bg-danger h-100">
            <div class="card-body d-flex flex-column text-center">
                <h5 class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-graph-up me-2" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M0 0h1v15h15v1H0V0zm4 11.293 3-3 2 2 4-4 1.5 1.5-5.5 5.5-2-2-3 3V11.293z"/>
                    </svg>
                    Average Tokens per Day
                </h5>
                <h2 class="fw-bold display-6">{{ $tokenAverage }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-dark bg-warning h-100">
            <div class="card-body d-flex flex-column text-center">
                <h5 class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-award me-2" viewBox="0 0 16 16">
                        <path d="M9.669.864 8 0 6.331.864 5.13.308A2 2 0 0 0 2.905 2.68l.338 1.574A2.99 2.99 0 0 0 2 7a3 3 0 0 0 6 0 2.99 2.99 0 0 0-.243-1.255l.338-1.574a2 2 0 0 0-2.225-2.372l-1.202.556L4.194.864zM8 1.5c.314 0 .63.045.937.133l-.339 1.575a2.99 2.99 0 0 0-1.196 0l-.339-1.575A3.003 3.003 0 0 1 8 1.5zM6 8a2 2 0 1 1 4 0A2 2 0 0 1 6 8z"/>
                    </svg>
                    Most Visited Counter
                </h5>
                <h2 class="fw-bold display-6 text-truncate" style="max-width: 100%;">{{ $mostVisitedCounter ?? 'No Data' }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-dark bg-success h-100">
            <div class="card-body d-flex flex-column text-center">
                <h5 class="card-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-ticket me-2" viewBox="0 0 16 16">
                        <path d="M0 4.5A1.5 1.5 0 0 1 1.5 3h13A1.5 1.5 0 0 1 16 4.5V6a2 2 0 1 0 0 4v1.5A1.5 1.5 0 0 1 14.5 13h-13A1.5 1.5 0 0 1 0 11.5V10a2 2 0 1 0 0-4V4.5zM1 10.93V11.5a.5.5 0 0 0 .5.5H4v-8H1.5a.5.5 0 0 0-.5.5v.57a3 3 0 0 1 0 5.86zM6 12h4V4H6v8zm5-8v8h2.5a.5.5 0 0 0 .5-.5v-.57a3 3 0 0 1 0-5.86V4.5a.5.5 0 0 0-.5-.5H11z"/>
                    </svg>
                    Total Tokens Today
                </h5>
                <h2 class="fw-bold display-6">{{ $totalTokens ?? 'No Data' }}</h2>
            </div>
        </div>
    </div>
</div>

@endsection
