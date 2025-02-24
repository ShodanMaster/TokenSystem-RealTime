<?php

namespace App\Exports\Admin\Counter;

use App\Models\Counter;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DetailedCounterReport implements FromArray, WithHeadings, WithCustomStartCell
{
    protected $counterName;
    protected $totalTokens;
    protected $counterId;

    public function __construct($counterName)
    {
        $this->counterName = $counterName;
        $counter = Counter::where('name', $counterName)->first();

        if ($counter) {
            $this->counterId = $counter->id;
            $this->totalTokens = DB::table('counter_token')
                ->where('counter_id', $counter->id)
                ->count();
        } else {
            $this->counterId = null;
            $this->totalTokens = 0;
        }
    }

    public function array(): array
    {
        if (!$this->counterId) {
            return [];
        }

        $tokensWithDate = DB::table('tokens')
            ->join('counter_token', 'tokens.id', '=', 'counter_token.token_id')
            ->where('counter_token.counter_id', $this->counterId)
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
            ["Detailed Report for Counter: " . $this->counterName . " | Total Tokens: " . $this->totalTokens],
            ["Date", "Token Number", "Name"]
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }
}
