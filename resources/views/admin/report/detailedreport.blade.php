@extends('admin.layout')
@section('content')
<div class="card shadow-lg">
    <div class="card-header bg-info text-white fs-4 text-center">
        Detailed Report on {{$token->date}}
    </div>
    <div class="card-body">
        <!-- Token Stats Section -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm p-3 text-center">
                    <h5>Total Token</h5>
                    <p class="fs-3">{{$token->total_token}}</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm p-3 text-center">
                    <h5>Last Went</h5>
                    <p class="fs-3">{{$token->last_went}}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm p-3 text-center">
                    <h5>Token Left</h5>
                    <p class="fs-3">{{$token->token_left}}</p>
                </div>
            </div>
        </div>

        <!-- Counter Token Details Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr class="bg-light">
                        <th>Counter Name</th>
                        <th>Token Number</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($counters as $counter)
                        <tr>
                            <td>{{$counter->counter->name}}</td>
                            <td>{{$counter->token_number}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">No Data Available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
