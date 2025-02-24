<?php

namespace App\Exports\Counter;


use App\Models\Token;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CounterReport implements FromArray, WithHeadings
{
    public function array(): array
    {
        $reports = Token::whereHas('counters', function ($query) {
            $query->where('counter_id', Auth::guard('counter')->user()->id);
        })
        ->latest()
        ->get()
        ->groupBy('date')
        ->map(function ($groupedTokens, $date) {
            return [
                'date' => $date,
                'total' => $groupedTokens->count(),
            ];
        })
        ->values()
        ->toArray();

        return $reports;
    }

    public function headings(): array
    {
        return [
            ["Reports of ". Auth::guard()->user()->name],
            ["Date", "Total Tokens"],
        ];
    }
}
