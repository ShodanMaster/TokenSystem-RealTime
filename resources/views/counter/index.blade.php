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

            setTimeout(() => {
                window.Echo.channel('adminevent').listen('.issued', (e)=>{
                    $('#totalTokenIssued').text(e.totalTokens);
                    $('#tokensLeft').text(e.tokensLeft);
                })
            }, 200);

            setTimeout(() => {
                window.Echo.channel('counterget').listen('.get', (e)=>{
                    $('#tokensLeft').text(e.tokensLeft);
                })
            }, 200);

            $.ajax({
                    type: "GET",
                    url: "{{route('counter.windowload')}}",
                    success: function (response) {
                        // console.log(response);
                        $('#totalTokenIssued').text(response.data.total);
                        $('#tokensLeft').text(response.data.total_left);
                        $('#counterToken').text("Token #" + response.data.token_number);
                    }
                });

            $(document).on('click', '#getToken', function (e) {
                e.preventDefault();

                var counterId = "{{ encrypt(Auth::guard('counter')->user()->id) }}";
                var tokenLabel = "#counterToken";

                $.ajax({
                    type: "GET",
                    url: "{{route('counter.gettoken', ':id')}}".replace(':id', counterId),
                    success: function (response) {

                        if (response.status === 200) {
                            $('#totalTokenIssued').text(response.data.total);
                            $('#tokensLeft').text(response.data.token_left);
                            $(tokenLabel).text("Token #" + response.data.last_went);
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#tokensLeft').text('0');
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
