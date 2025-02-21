<?php

namespace App\Http\Controllers\Counter;

use App\Http\Controllers\Controller;
use App\Models\Token;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(){
        $reports = Token::whereHas('counters', function ($query) {
                $query->where('counter_id', Auth::guard('counter')->user()->id);
            })
            ->latest()
            ->get()
            ->groupBy('date') // Group by the date column
            ->map(function($groupedTokens) {
                return (object)[
                    'date' => $groupedTokens->first()->date, // Get the date for the group
                    'total' => $groupedTokens->count(), // Total count of tokens for that date
                    'id' => $groupedTokens->first()->date // Include an ID for detailed report
                ];
            });

            // dd($reports);

        return view('counter.report.index', compact('reports'));
    }

    public function detailedReport($date){
        // dd($id);
        try{

            $startOfDay = Carbon::parse($date)->startOfDay();
            $endOfDay = Carbon::parse($date)->endOfDay();

            $tokens = Token::whereHas('counters', function ($query) {
                $query->where('counter_id', Auth::guard('counter')->user()->id);
            })->whereBetween('created_at', [$startOfDay, $endOfDay])
                        ->latest()
                        ->get();
            // dd($tokens);
            if ($tokens) {
                return view('counter.report.detailedreport', compact('tokens', 'date'));
            } else {
                return redirect()->back()->with('error', 'Details Not Found');
            }
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Something Went Wrong! '.$e->getMessage());
        }
    }
}
