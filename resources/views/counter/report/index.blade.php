@extends('counter.layout')
@section('content')
    <h1>Reports</h1>
    <div class="card shadow-lg">
        <div class="card-header bg-info text-white fs-4">
            Reports
        </div>
        <div class="card-body">
            <table class="table" id="reportsTable">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Total</th>
                        <th scope="col">Date</th>
                        <th scope="col">Detailed</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Rows will be populated by DataTables -->
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')

<script>
    $(document).ready(function() {
        $('#reportsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('counter.getreport') }}', // Make sure this route is correctly defined
                type: 'GET',
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'total', name: 'total' },
                { data: 'date', name: 'date' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    });
</script>
@endsection
