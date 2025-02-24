<?php

namespace App\Exports\Admin\Token;

use App\Models\Token;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DetailedTokenReport implements FromArray, WithHeadings, WithCustomStartCell
{
    protected $date;
    protected $totalTokens;

    public function __construct($date)
    {
        $this->date = $date;
        $this->totalTokens = Token::whereBetween('created_at', [
            Carbon::parse($date)->startOfDay(),
            Carbon::parse($date)->endOfDay()
        ])->count();
    }

    public function array(): array
    {
        $startOfDay = Carbon::parse($this->date)->startOfDay();
        $endOfDay = Carbon::parse($this->date)->endOfDay();

        $tokens = Token::with('counters')
            ->whereBetween('created_at', [$startOfDay, $endOfDay])
            ->latest()
            ->get();

        return $tokens->map(function ($token) {
            return [
                'date' => $token->created_at->format('Y-m-d'),
                'token_number' => $token->token_number,
                'name' => $token->name,
                'counter' => $token->counters->isEmpty()
                    ? 'No Counter'
                    : $token->counters->pluck('name')->implode(', '),
            ];
        })->toArray();
    }

    public function headings(): array
    {
        return [
            ["Detailed Report for Tokens: " . $this->date . " | Total Tokens: " . $this->totalTokens],
            ["Date", "Token Number", "Name", "Counter"],
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }
}
