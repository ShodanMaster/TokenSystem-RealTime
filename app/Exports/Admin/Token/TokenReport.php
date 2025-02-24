<?php

namespace App\Exports\Admin\Token;

use App\Models\Token;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TokenReport implements FromArray, WithHeadings
{
    public function array(): array
    {
        $tokens = Token::latest()->get()->groupBy('date');

        return $tokens->map(function ($groupedTokens, $date) {
            return [
                'date' => $date,
                'total' => $groupedTokens->count(),
                'token_left' => $groupedTokens->where('status', 0)->count(),
            ];
        })->values()->toArray();
    }

    public function headings(): array
    {
        return ["Date", "Total Tokens", "Tokens Left"];
    }
}
