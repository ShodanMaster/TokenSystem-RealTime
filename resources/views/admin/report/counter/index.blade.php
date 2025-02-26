@extends('admin.layout')
@section('content')

    <div class="card shadow-lg">
        <div class="card-header bg-info text-white fs-4 d-flex justify-content-between">
            Counter Report

            <a href="{{route('admin.exportcounterreport')}}"><button class="btn btn-success">Excel</button></a>
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
                </tbody>
            </table>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#counterReportTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.getcounterreport') !!}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'name', name: 'name' },
                    { data: 'tokens_count', name: 'tokens_count' },
                    { data: 'date', name: 'date' },
                    {
                        data: null,
                        render: function(data, type, row) {
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
