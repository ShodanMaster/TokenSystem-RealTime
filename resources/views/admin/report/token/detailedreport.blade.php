@extends('admin.layout')
@section('content')
<div class="d-flex justify-content-end mb-2">
    <a href="{{route('admin.exportdetailedtoken', $date)}}"><button class="btn btn-success">Excel</button></a>
</div>
<div class="card shadow-lg">
    <div class="card-header bg-info text-white fs-4 text-center">
        Detailed Report on {{$date}}
    </div>
    <div class="card-body">
        <!-- Token Stats Section -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm p-3 text-center">
                    <h5>Total Tokens</h5>
                    <p class="fs-3">{{ count($tokens) }}</p>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                    <div class="card shadow-sm p-3 text-center">
                        <h5>Total Left</h5>
                        <p class="fs-3">{{ count($tokens->filter(function($t) { return $t->status === 0; })) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Counter Token Details Table -->
        <div class="table-responsive">
            <table id="tokenReportTable" class="table table-bordered table-striped table-hover">
                <thead>
                    <tr class="bg-light">
                        <th>#</th>
                        <th>Name</th>
                        <th>Token Number</th>
                        <th>Counter</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- DataTable will populate this dynamically -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            var date = "{{ $date }}"; // Get the date from the controller
            $('#tokenReportTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.getdetailedtokenreport', ['date' => $date]) !!}', // URL for AJAX request
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' }, // Auto-increment index column
                    { data: 'name', name: 'name' }, // Display token name
                    { data: 'token_number', name: 'token_number' }, // Display token number
                    { data: 'counter', name: 'counter' } // Display counter(s) (No Counter or list of counters)
                ]
            });
        });

    </script>
@endsection
