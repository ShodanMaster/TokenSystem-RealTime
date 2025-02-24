@extends('admin.layout')
@section('content')

    <div class="card shadow-lg">
        <div class="card-header bg-info text-white fs-4 d-flex justify-content-between">
            Token Report

            <a href="{{route('admin.exporttokenreport')}}"><button class="btn btn-success">Excel</button></a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="tokenReport">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Total</th>
                            <th scope="col">Token left</th>
                            <th scope="col">Date</th>
                            <th scope="col">Detailed</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#tokenReport').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('admin.gettokenreport')}}",  // The correct route for your server-side method
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'total', name: 'total' },   // Make sure the server-side response has 'total'
                { data: 'token_left', name: 'token_left' },  // Same for 'token_left'
                { data: 'date', name: 'date' },   // 'date' from the response
                { data: 'action', name: 'action', orderable: false, searchable: false }  // 'action' for the buttons
            ],
        });
    });
</script>
@endsection
