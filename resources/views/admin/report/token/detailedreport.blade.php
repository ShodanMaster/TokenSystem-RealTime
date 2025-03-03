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
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            var date = "{{ $date }}";
            $('#tokenReportTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! route('admin.getdetailedtokenreport', ['date' => $date]) !!}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'name', name: 'name' },
                    { data: 'token_number', name: 'token_number' },
                    { data: 'counter', name: 'counter' }
                ]
            });
        });

    </script>
@endsection
