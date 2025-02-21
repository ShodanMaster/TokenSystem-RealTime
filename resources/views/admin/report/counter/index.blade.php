@extends('admin.layout')
@section('content')

    <div class="card shadow-lg">
        <div class="card-header bg-info text-white fs-4">
            Counter Report
        </div>
        <div class="card-body">
            <table class="table" id="counterReportTable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Tokens</th>
                        <th scope="col">Date</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- This tbody will be populated by DataTables -->
                </tbody>
            </table>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#counterReportTable').DataTable({
                processing: true,  // Show loading indicator
                serverSide: true,  // Enable server-side processing
                ajax: '{!! route('admin.getcounterreport') !!}', // The AJAX URL to fetch data
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' }, // Auto-incrementing index column
                    { data: 'name', name: 'name' },               // Counter name
                    { data: 'tokens_count', name: 'tokens_count' }, // Token count
                    { data: 'date', name: 'date' },              // Date
                    {
                        data: null,
                        render: function(data, type, row) {
                            // Render button with link to detailed report
                            return `<a href="{{ route('admin.detailedcounterreport', '') }}/${row.name}">
                                        <button class="btn btn-info">Detailed</button>
                                    </a>`;
                        },
                        searchable: false,
                        orderable: false
                    }  // Action button
                ]
            });
        });

    </script>
@endsection
