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
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Counter</th>
                    <th scope="col">Open/Close</th>
                    {{-- <th scope="col">Action</th> --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($counters as $counter)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$counter->name}}</td>
                        <td>
                            <span class="btn btn-sm {{$counter->closed ? 'btn-danger' : 'btn-success'}} rounded"
                                  id="statusButton"
                                  data-closed="{{ $counter->closed }}"
                                  data-counter-id="{{ encrypt($counter->id) }}">  <!-- Store counter ID here -->
                                @if ($counter->closed)
                                    CLOSED
                                @else
                                    OPEN
                                @endif
                            </span>
                        </td>
                        {{-- <td>
                            <button type="button" class="btn btn-danger" id="deleteCounter" data-counter-id="{{ encrypt($counter->id) }}">Delete</button>
                        </td> --}}
                    </tr>
                @empty
                    <tr><td class="text-center text-muted" colspan="3">No Counters</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
    <script>
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
                                    location.reload();
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
            var currentStatus = button.data('closed');  // Get the current status (open/closed)

            // Determine the new status by toggling the current status
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
                            counter_id: counterId,  // Include counter_id
                            closed: newStatus,      // Send new status
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
                                    location.reload();
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

        // $(document).on('click', '#deleteCounter',function (e) {
        //     e.preventDefault();

        //     var button = $(this);
        //     var counterId = button.data('counter-id');

        //     Swal.fire({
        //         title: 'Delete Counter',
        //         text: 'Do you want to delete this counter?',
        //         icon: 'danger',
        //         showCancelButton: true,
        //         confirmButtonText: 'Yes, delete.',
        //         cancelButtonText: 'No, cancel!',
        //         reverseButtons: true
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $.ajax({
        //                 url: "{{ route('admin.deletecounter') }}",
        //                 method: 'POST',
        //                 data: {
        //                     counter_id: counterId,
        //                     _token: '{{ csrf_token() }}',
        //                 },
        //                 success: function(response) {
        //                     if (response.status === 'success') {
        //                         Swal.fire({
        //                             icon: 'success',
        //                             title: 'Updated',
        //                             text: response.message,
        //                             confirmButtonText: 'OK'
        //                         }).then(function() {
        //                             location.reload();
        //                         });
        //                     } else {
        //                         Swal.fire({
        //                             icon: 'error',
        //                             title: 'Error',
        //                             text: response.message,
        //                             confirmButtonText: 'OK'
        //                         }).then(function() {
        //                             location.reload();
        //                         });
        //                     }
        //                 },
        //                 error: function(xhr, status, error) {
        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: 'Oops...',
        //                         text: 'Something went wrong. Please try again.',
        //                         confirmButtonText: 'OK'
        //                     });
        //                 }
        //             });
        //         }
        //     });
        // });

    </script>
@endsection
