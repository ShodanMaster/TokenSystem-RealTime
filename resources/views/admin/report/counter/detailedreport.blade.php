@extends('admin.layout')
@section('content')
<div class="card shadow-lg">
    <div class="card-header bg-info text-white fs-4 text-center">
        Detailed Report for Counter: {{$counter->name}}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr class="bg-light">
                        <th>Name</th>
                        <th>Token Number</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tokensWithDate as $token)
                        <tr>
                            <td>{{ $token->name }}</td>
                            <td>{{ $token->token_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($token->date)->format('d-m-Y') }}</td>
                        </tr>
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
