@extends('admin.layout')
@section('content')
<div class="card shadow-lg">
    <div class="card-header bg-info text-white fs-4 text-center">
        Detailed Report for Counter: {{$counter->name}}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="counterReportTable">
                <thead>
                    <tr class="bg-light">
                        <th>Name</th>
                        <th>Token Number</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be filled by DataTable via Ajax -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
        $('#counterReportTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("admin.getdetailedcounterreport", $counter->name) }}', // Ensure this route matches the route in your web.php
            columns: [
                { data: 'name', name: 'name' },
                { data: 'token_number', name: 'token_number' },
                { data: 'date', name: 'date' },
            ],
            order: [[2, 'desc'], [1, 'desc']], // Orders by date and token_number
        });
    });
    </script>
@endsection
