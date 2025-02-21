<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\CounterToken;
use App\Models\Token;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function tokenReport(){
                return view('admin.report.token.index');
    }

    public function getTokenReport(Request $request)
    {
        $reports = Token::latest()
            ->get()
            ->groupBy('date') // Group by the date column
            ->map(function($groupedTokens) {
                return (object)[
                    'date' => $groupedTokens->first()->date, // Get the date for the group
                    'total' => $groupedTokens->count(), // Total count of tokens for that date
                    'token_left' => $groupedTokens->where('status', false)->count(), // Count tokens where 'status' is false
                    'id' => $groupedTokens->first()->date // Include an ID for detailed report
                ];
            });

        if($request->ajax()){
            return DataTables::of($reports)
                ->addIndexColumn()
                ->addColumn('action', function($report){
                    return '<a href="' . route('admin.detailedtokenreport', $report->date) . '">
                                <button class="btn btn-info">Detailed</button>
                            </a>';
                })
                ->make(true);
        }
    }

    public function detailedTokenReport($date){
        $startOfDay = Carbon::parse($date)->startOfDay();
        $endOfDay = Carbon::parse($date)->endOfDay();
        $tokens = Token::with('counters')->whereBetween('created_at', [$startOfDay, $endOfDay])
                    ->latest()
                    ->get();
        return view('admin.report.token.detailedreport', compact('tokens', 'date'));
    }

    public function getDetailedTokenReport(Request $request, $date){
        $startOfDay = Carbon::parse($date)->startOfDay();
        $endOfDay = Carbon::parse($date)->endOfDay();

        $tokens = Token::with('counters')->whereBetween('created_at', [$startOfDay, $endOfDay])
                    ->latest()
                    ->get();

        if ($request->ajax()) {
            return DataTables::of($tokens)
                ->addIndexColumn()
                ->addColumn('name', function($token) {
                    return $token->name;
                })
                ->addColumn('token_number', function($token) {
                    return $token->token_number;
                })
                ->addColumn('counter', function($token) {
                    if ($token->counters->isEmpty()) {
                        return 'No Counter'; // No counter assigned
                    } else {
                        // If multiple counters, loop through them
                        return $token->counters->pluck('name')->implode(', '); // Combines the counter names if more than one
                    }
                })
                ->make(true);
        }
    }


    public function counterReport(){
        $counters = Counter::latest()
        ->get()
        ->map(function($counter){
            $tokensCount = $counter->tokens()
            ->whereDate('tokens.created_at', $counter->created_at->toDateString())
            ->count();

            return (object)[
                'name' => $counter->name,
                'date' => $counter->created_at->toDateString(),
                'tokens_count' => $tokensCount,
            ];
        });

        // dd($counters);

    return view('admin.report.counter.index', compact('counters'));
    }

    public function detailedCounterReport($counter)
    {
        try {
            $counter = Counter::where('name', $counter)->first();

            if (!$counter) {
                return redirect()->back()->with('error', 'Counter not found.');
            }

            // Get all tokens for the counter with their specific creation date
            $tokensWithDate = DB::table('tokens')
                ->join('counter_token', 'tokens.id', '=', 'counter_token.token_id')
                ->where('counter_token.counter_id', $counter->id)
                ->select('tokens.date', 'tokens.token_number', 'tokens.name','counter_token.counter_id', 'counter_token.token_id')
                ->orderBy('tokens.date', 'desc')
                ->orderBy('tokens.token_number', 'desc')
                ->get();

            if ($tokensWithDate->isNotEmpty()) {
                return view('admin.report.counter.detailedreport', compact('tokensWithDate', 'counter'));
            } else {
                return redirect()->back()->with('error', 'Details Not Found');
            }

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something Went Wrong! ' . $e->getMessage());
        }
    }



}
