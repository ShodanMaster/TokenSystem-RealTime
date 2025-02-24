@extends('counter.layout')
@section('content')
<div class="d-flex justify-content-end mb-2">
    <a href="{{route('counter.getdetailedreportexcel',  $date)}}"><button class="btn btn-success">Excel</button></a>
</div>
<div class="card shadow-lg">
    <div class="card-header bg-info text-white fs-4 text-center">
        Detailed Report on {{$date}}
    </div>
    <div class="card-body">
        <!-- Token Stats Section -->

        <div class="mb-3">
            <div class="card shadow-sm p-3 text-center">
                <h5>Total Tokens</h5>
                <p class="fs-3">{{ count($tokens) }}</p>
            </div>
        </div>



        <!-- Counter Token Details Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="tokens-table">
                <thead>
                    <tr class="bg-light">
                        <th>#</th>
                        <th>Name</th>
                        <th>Token Number</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Initially, data will be empty, it will be loaded by DataTables --}}
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#tokens-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route("counter.getdetailedreport", $date) }}', // AJAX route to fetch data
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'name', name: 'name' },
                    { data: 'token_number', name: 'token_number' }
                ]
            });
        });
    </script>
@endsection
