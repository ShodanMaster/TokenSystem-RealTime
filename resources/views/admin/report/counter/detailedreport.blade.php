@extends('admin.layout')
@section('content')

<div class="d-flex justify-content-end mb-2">
    <a href="{{route('admin.exportdetailedcounter', $counter->name)}}"><button class="btn btn-success">Excel</button></a>
</div>
<div class="card shadow-lg">
    <div class="card-header bg-info text-white fs-4 text-center">
        Detailed Report for Counter: {{$counter->name}}
    </div>
    <div class="card-body">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm p-3 text-center">
                <h5>Total Tokens</h5>
                <p class="fs-3">{{ $tokenCount }}</p>
            </div>
        </div>
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
            ajax: '{{ route("admin.getdetailedcounterreport", $counter->name) }}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'token_number', name: 'token_number' },
                { data: 'date', name: 'date' },
            ],
            order: [[2, 'desc'], [1, 'desc']],
        });
    });
    </script>
@endsection
