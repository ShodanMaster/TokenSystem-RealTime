@extends('admin.layout')
@section('content')

<div class="card">
    <div class="card-header bg-success text-white fs-4 d-flex justify-content-between">
        Counter
        <button type="button" id="addCounter" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-square"></i>
        </button>
    </div>
    <div class="card-body">
        <table class="table table-striped" id="counterTable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Counter</th>
                    <th scope="col">Open/Close</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

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

            var table = $('#counterTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{route('admin.getcounters')}}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {data : 'name'},
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
            });


            $(document).on('click', '#addCounter',function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Create Counter',
                    text: 'Do you want to Create Counter?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, create.',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "GET",
                            url: "{{ route('admin.createcounter') }}",
                            success: function(response) {
                                if (response.status == 200) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Created',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    }).then(function() {
                                        table.ajax.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    }).then(function() {
                                        location.reload();
                                    });
                                }
                            },

                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong. Please try again.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

            $(document).on('click', '#statusButton', function (e) {
                e.preventDefault();
                var button = $(this);
                var counterId = button.data('counter-id');
                var currentStatus = button.data('closed');

                var newStatus = currentStatus ? 0 : 1;

                console.log('Counter ID:', counterId);
                console.log('Current Status:', currentStatus);
                console.log('New Status:', newStatus);

                Swal.fire({
                    title: 'Change Counter Status',
                    text: 'Do you want to change the status of this counter?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, update.',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.updatestatus') }}",
                            method: 'POST',
                            data: {
                                counter_id: counterId,
                                closed: newStatus,
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                if (response.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Updated',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    }).then(function() {
                                        table.draw();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message,
                                        confirmButtonText: 'OK'
                                    }).then(function() {
                                        location.reload();
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Something went wrong. Please try again.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        });
                    }
                });
            });

        });

    </script>
@endsection
