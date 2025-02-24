<?php

namespace App\Exports;

use App\Models\Counter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CounterReport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Counter::latest()
        ->get()
        ->map(function ($counter) {
            $tokensCount = $counter->tokens()
                ->whereDate('tokens.created_at', $counter->created_at->toDateString())
                ->count();

            return [
                'name' => $counter->name,
                'date' => $counter->created_at->toDateString(),
                'tokens_count' => $tokensCount,
            ];
        });
    }

    public function headings(): array
    {
        return ["Counter", "Date", "Tokens Count"];
    }
}
