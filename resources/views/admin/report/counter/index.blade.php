@extends('admin.layout')
@section('content')
    <h1>Counters</h1>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">name</th>
                <th scope="col">tokens</th>
                <th scope="col">Date</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($counters as $counter)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$counter->name}}</td>
                    <td>{{$counter->tokens_count}}</td>
                    <td>{{$counter->date}}</td>
                    <td>
                        <!-- Button link to the detailed counter -->
                        <a href="{{route('admin.detailedcounterreport', $counter->name)}}">
                            <button class="btn btn-info">Detailed</button>
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td class="text-center text-muted" colspan="5">No counters available.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
