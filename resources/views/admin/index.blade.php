@extends('admin.layout')
@section('content')
    <h1>Admin Dashboard</h1>

    <div class="row">
        <div class="col-md-6 ">
            <h1>Total Issued Tokens: <span id="totalTokens">0</span></h1>
        </div>
        <div class="col-md-6 ">
            <h3>Last Went: <span id="lastWent">0</span></h3>
            <h3>Token Left: <span id="tokenLeft">0</span></h3>
        </div>
    </div>
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-success text-white text-center fs-4">
            Add Token
        </div>
        <div class="card-body">
            <form action="" id="tokenForm">
                <div class="form-group">
                    <input type="text" class="form-control form-control-lg" name="name" placeholder="Enter Name" required>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="submit" class="btn btn-success btn-lg">Issue Token</button>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('scripts')
    <script>

        $(document).ready(function () {

            $.ajax({
                type: "GET",
                url: "{{route('admin.windowload')}}",
                success: function (response) {
                    updateTokenDisplay(response.data.total, response.data.token_number, response.data.total_left);
                }
            });

            $(document).on('submit', '#tokenForm', function (e) {
                e.preventDefault();

                $('.btn-success').prop('disabled', true);
                $('.btn-success').text('Issuing...');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var formData = new FormData(this);

                $.ajax({
                    type: "POST",
                    url: "{{route('admin.addtoken')}}",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        // console.log(response);

                        if (response.status === 200) {
                            $('#tokenForm')[0].reset();
                            // Update UI with the total tokens and token left
                            updateTokenDisplay(response.data.total, response.data.token_number, response.data.total_left);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle errors such as network issues, or invalid response
                        console.error("Error: " + error);
                        alert('An error occurred: ' + error);
                    },
                    complete: function() {
                        $('.btn-success').prop('disabled', false);
                        $('.btn-success').text('Issue Token');
                    }
                });

            });

            function updateTokenDisplay(total, token_number, token_left) {

                $('#totalTokens').text(total);
                $('#lastWent').text(token_number);
                $('#tokenLeft').text(token_left);

            }

        });

    </script>
@endsection
