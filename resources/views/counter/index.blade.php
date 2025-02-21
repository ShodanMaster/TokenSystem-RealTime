@extends('counter.layout')
@section('content')

        <h1>{{ Auth::guard('counter')->user()->name }}</h1>
        <table class="table table-striped">
            <tr>
                <th>Total Issued Tokens: </th>
                <td id="totalTokenIssued"></td>
            </tr>
            <tr>
                <th>Tokens Left: </th>
                <td id="tokensLeft"></td>
            </tr>
        </table>
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white text-center fs-4">
                Counter
            </div>
            <div class="card-body text-center">
                <h5 class="card-title" id="counterToken">No Token</h5>
            </div>
            <div class="card-footer text-center">
                <button class="btn btn-success btn-lg w-100" id="getToken">Get Token</button>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>

        $(document).ready(function () {

            $.ajax({
                    type: "GET",
                    url: "{{route('counter.windowload')}}",
                    success: function (response) {
                        console.log(response);

                        // updateTokenDisplay(response.data.total, response.data.token_number, response.data.total_left);
                        $('#totalTokenIssued').text(response.data.total);
                        $('#tokensLeft').text(response.data.total_left);
                        $('#counterToken').text("Token #" + response.data.token_number);
                    }
                });

            $(document).on('click', '#getToken', function (e) {
                e.preventDefault();

                var counterId = "{{ encrypt(Auth::guard('counter')->user()->id) }}"; // Get the counter ID dynamically from the session or user
                var tokenLabel = "#counterToken"; // Get the corresponding token label

                $.ajax({
                    type: "GET",
                    url: "{{route('counter.gettoken', ':id')}}".replace(':id', counterId),  // Replace counter ID dynamically in URL
                    success: function (response) {
                        console.log(response);

                        if (response.status === 200) {
                            // Update the HTML elements with the new token data
                            $('#totalTokenIssued').text(response.data.total);
                            $('#tokensLeft').text(response.data.token_left);
                            $(tokenLabel).text("Token #" + response.data.last_went);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error if no tokens are available
                        $('#tokensLeft').text('0');
                        // $(tokenLabel).text("Token #0");
                        var errorResponse = JSON.parse(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: errorResponse.message,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endsection
