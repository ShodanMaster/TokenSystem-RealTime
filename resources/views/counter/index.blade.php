<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Token System</title>
    <link rel="stylesheet" href="{{asset('cropper/cropper.min.css')}}">
    <link rel="stylesheet" href="{{asset('bootstrap/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('sweetalert/sweetalert2.min.css')}}">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between">
            <h1>{{ Auth::guard('counter')->user()->name }}</h1>
            <a href="{{route('counter.loggingout')}}"><button class="btn btn-danger" type="button">Logout</button></a>
        </div>
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

<script src="{{asset('js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset('cropper/cropper.min.js')}}"></script>
<script src="{{asset('bootstrap/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('sweetalert/sweetalert2.min.js')}}"></script>

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
                    $(tokenLabel).text("Token #0");
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

</body>
</html>
