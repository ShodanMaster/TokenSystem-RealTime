@extends('admin.layout')
@section('content')
    <h1>Reports</h1>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Total</th>
                <th scope="col">Token left</th>
                <th scope="col">Date</th>
                <th scope="col">Detailed</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reports as $report)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$report->total}}</td>
                    <td>{{$report->token_left}}</td>
                    <td>{{$report->date}}</td>
                    <td>
                        <!-- Button link to the detailed report -->
                        <a href="{{ route('admin.detailedreport', $report->date) }}">
                            <button class="btn btn-info">Detailed</button>
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td class="text-center text-muted" colspan="5">No reports available.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
