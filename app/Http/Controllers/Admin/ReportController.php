<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CounterToken;
use App\Models\Token;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(){
        $reports = Token::latest()
            ->get()
            ->groupBy('date') // Group by the date column
            ->map(function($groupedTokens) {
                return (object)[
                    'date' => $groupedTokens->first()->date, // Get the date for the group
                    'total' => $groupedTokens->count(), // Total count of tokens for that date
                    'token_left' => $groupedTokens->where('status', false)->count(), // Count tokens where 'true' is false
                    'id' => $groupedTokens->first()->date // Include an ID for detailed report
                ];
            });
            // dd($reports);

        return view('admin.report.index', compact('reports'));
    }

    public function detailedReport($date){
        // dd($id);
        try{
            // $token = Token::whereDate('date',$date)->latest()->get();


                // $date = $token->date;

            $startOfDay = Carbon::parse($date)->startOfDay();
            $endOfDay = Carbon::parse($date)->endOfDay();

            $tokens = Token::whereBetween('created_at', [$startOfDay, $endOfDay])
                        ->latest()
                        ->get();
            // dd($tokens);
            if ($tokens) {
                return view('admin.report.detailedreport', compact('tokens', 'date'));
            } else {
                return redirect()->back()->with('error', 'Details Not Found');
            }
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Something Went Wrong! '.$e->getMessage());
        }
    }
}
