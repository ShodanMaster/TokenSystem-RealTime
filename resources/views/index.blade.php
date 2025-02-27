<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Token System</title>
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('sweetalert/sweetalert2.min.css') }}">
    @vite('resources/js/app.js')
</head>
<body>

    <div class="container my-5">
        <div class="d-flex justify-content-between">
            <h1 class="text-center mb-4">Token System</h1>
            <h1>Total Tokens: <span id="totalTokenIssued">0</span></h1>
        </div>

        <div class="row g-4">
            @forelse ($counters as $counter)
            <div class="col-md-4 col-sm-6">
                <div class="card shadow-sm border-0 rounded text-center p-3">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-uppercase">{{ $counter->name }}</h5>
                        <p class="token-number text-muted" data-counter-name="{{ $counter->name }}">Waiting...</p>
                    </div>
                </div>
            </div>
            @empty
            <h4 class="text-muted text-center">No Counters</h4>
            @endforelse
        </div>
    </div>

    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('sweetalert/sweetalert2.min.js') }}"></script>

    <script>
        $(document).ready(function(){
            setTimeout(() => {

                window.Echo.channel('adminevent').listen('.issued', (e)=>{
                    $('#totalTokenIssued').text(e.totalTokens);
                })

                window.Echo.channel('index.notifications').listen('.token.received', (e) => {
                    console.log(e);

                    if (e.lastFiveData) {
                        let latestToken = e.lastFiveData[0];
                        $(`.token-number[data-counter-name="${latestToken.counter}"]`).text(`Token: ${latestToken.token_number}`);
                    }
                });

            }, 200);

            $.ajax({
                type: "GET",
                url: "{{route('windowload')}}",
                success: function (response) {
                    if (response.status === 200) {
                        $.each(response.data, function(index, item) {
                            let selector = `.token-number[data-counter-name="${item.counter}"]`;
                            $(selector).text(`Token: ${item.token}`);
                        });
                        $('#totalTokenIssued').text(response.totalTokens);
                    }
                }
            });

        });
    </script>

</body>
</html>
