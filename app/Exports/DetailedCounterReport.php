<?php

namespace App\Exports;

use App\Models\Counter;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DetailedCounterReport implements FromArray, WithHeadings, WithCustomStartCell
{
    protected $counterName;

    public function __construct($counterName)
    {
        $this->counterName = $counterName;
    }

    public function array(): array
    {
        $counter = Counter::where('name', $this->counterName)->first();

        if (!$counter) {
            return [];
        }

        $tokensWithDate = DB::table('tokens')
            ->join('counter_token', 'tokens.id', '=', 'counter_token.token_id')
            ->where('counter_token.counter_id', $counter->id)
            ->select('tokens.date', 'tokens.token_number', 'tokens.name')
            ->orderBy('tokens.date', 'desc')
            ->get();

        return $tokensWithDate->map(function ($token) {
            return [
                $token->date,
                $token->token_number,
                $token->name,
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return [
            ["Detailed Report for Counter: " . $this->counterName],
            ["Date", "Token Number", "Name"]
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }
}
