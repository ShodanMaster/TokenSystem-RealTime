@extends('admin.layout')
@section('content')

    <h4>{{$date}}</h4>
    <div class="row">
        <div class="col-md-8">
            <h1>Total Issued Tokens: <span id="totalTokens">0</span></h1>
        </div>
        <div class="col-md-4">
            <h1>Token Left: <span id="tokenLeft">0</span></h1>
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
    <table class="table" id="counterToken">
        <thead>
            <tr>
                <th scope="col">Counter</th>
                <th scope="col">Token</th>
                <th scope="col">Name</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

@endsection
@section('scripts')

    <script>

        $(document).ready(function () {

            setTimeout(() => {
                window.Echo.channel('counterget').listen('.get', (e)=>{
                    $('#lastWent').text(e.lastWent);
                    $('#tokenLeft').text(e.tokensLeft);
                })

                window.Echo.private('admin.notifications').listen('.token.received', (data) => {
                    console.log(data.lastFiveData);
                    const lastFiveTokens = Array.isArray(data.lastFiveData) ? data.lastFiveData : [];
                    updateTokenTable(lastFiveTokens);
                });

            }, 200);

            $.ajax({
                type: "GET",
                url: "{{route('admin.windowload')}}",
                success: function (response) {
                    console.log(response);

                    updateTokenDisplay(response.data.total, response.data.total_left);
                    updateTokenTable(response.data.last_five);
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

                        if (response.status === 200) {
                            $('#tokenForm')[0].reset();
                            updateTokenDisplay(response.data.total, response.data.total_left);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error: " + error);
                        alert('An error occurred: ' + error);
                    },
                    complete: function() {
                        $('.btn-success').prop('disabled', false);
                        $('.btn-success').text('Issue Token');
                    }
                });

            });

            function updateTokenDisplay(total, token_left) {
                $('#totalTokens').text(total);
                $('#tokenLeft').text(token_left);

            }

            function updateTokenTable(lastFiveTokens){
                let tableBody = $("#counterToken tbody");
                tableBody.empty();

                if (lastFiveTokens.length > 0) {
                    lastFiveTokens.forEach(token => {
                        tableBody.append(`
                            <tr>
                                <td>${token.counter}</td>
                                <td>${token.token_number}</td>
                                <td>${token.name}</td>
                            </tr>
                        `);
                    });
                } else {
                    tableBody.append(`
                        <tr>
                            <td colspan="3" class="text-center text-muted">No Data</td>
                        </tr>
                    `);
                }
            }

        });

    </script>
@endsection
