@extends('admin.layout')
@section('content')
<div class="card shadow-lg">
    <div class="card-header bg-info text-white fs-4 text-center">
        Detailed Report on {{$date}}
    </div>
    <div class="card-body">
        <!-- Token Stats Section -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm p-3 text-center">
                    <h5>Total Tokens</h5>
                    <p class="fs-3">{{ count($tokens) }}</p>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                    <div class="card shadow-sm p-3 text-center">
                        <h5>Total Tokens</h5>
                        <p class="fs-3">{{ count($tokens->filter(function($t) { return $t->status === 0; })) }}</p>
                    </div>
                </div>
            </div>
        </div>


        <!-- Counter Token Details Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr class="bg-light">
                        <th>Name</th>
                        <th>Token Number</th>
                        <th>Counter</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tokens as $token)
                        @if ($token->counters->isEmpty())
                            <!-- If the token has no counters -->
                            <tr>
                                <td>{{ $token->name }}</td>
                                <td>{{ $token->token_number }}</td>
                                <td>No Counter</td> <!-- Display message if no counter -->
                            </tr>
                        @else
                            @foreach ($token->counters as $counter) <!-- Loop through counters if any -->
                                <tr>
                                    <td>{{ $token->name }}</td>
                                    <td>{{ $token->token_number }}</td>
                                    <td>{{ $counter->name }}</td> <!-- Display counter name -->
                                </tr>
                            @endforeach
                        @endif
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No Data Available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
