<?php

namespace App\Exports\Counter;

use App\Models\Token;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class DetailedReport implements FromArray, WithHeadings, WithCustomStartCell
{
    protected $date;
    protected $totalTokens;
    public function __construct($date)
    {
        $this->date = $date;


        $startOfDay = Carbon::parse($this->date)->startOfDay();
        $endOfDay = Carbon::parse($this->date)->endOfDay();

        $tokens = Token::whereHas('counters', function ($query) {
            $query->where('counter_id', Auth::guard('counter')->user()->id);
        })
        ->whereBetween('created_at', [$startOfDay, $endOfDay])
        ->latest()
        ->get();

        $this->totalTokens = $tokens->count();

    }

    public function array(): array
    {
        $startOfDay = Carbon::parse($this->date)->startOfDay();
        $endOfDay = Carbon::parse($this->date)->endOfDay();

        $tokens = Token::whereHas('counters', function ($query) {
            $query->where('counter_id', Auth::guard('counter')->user()->id);
        })
        ->whereBetween('created_at', [$startOfDay, $endOfDay])
        ->latest()
        ->get();

        return $tokens->map(function ($token) {
            return [
                'Date' => $token->created_at->format('Y-m-d'),
                'Token Number' => $token->token_number,
                'Name' => $token->name,
            ];
        })->toArray();
    }

    public function headings(): array
    {
        // dd($this->totalTokens);
        return [
            ["Detailed Report for " . $this->date ." Total Tokens: ". $this->totalTokens],
            ["Date", "Token Number", "Name"]
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }
}
