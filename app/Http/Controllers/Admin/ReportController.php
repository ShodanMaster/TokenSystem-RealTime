<?php

namespace App\Http\Controllers\Admin;

use App\Exports\Admin\Counter\CounterReport;
use App\Exports\Admin\Counter\DetailedCounterReport;
use App\Exports\Admin\Token\DetailedTokenReport;
use App\Exports\Admin\Token\TokenReport;
use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Token;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{

    public function tokenReport(){
        return view('admin.report.token.index');
    }

    public function getTokenReport(Request $request){

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

    public function tokenReportExcel(){
        return Excel::download(new TokenReport, 'token_report.xlsx');
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
                        return 'No Counter';
                    } else {
                        return $token->counters->pluck('name')->implode(', ');
                    }
                })
                ->make(true);
        }
    }


    public function detailedTokenReportExcel($date)
    {
        return Excel::download(new DetailedTokenReport($date), $date.'_token_report.xlsx');
    }

    public function counterReport(){
        return view('admin.report.counter.index');
    }

    public function getCounterReport(Request $request){

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

        if($request->ajax()){
            return DataTables::of($counters)
                ->addIndexColumn()
                ->addColumn('name', function($counter) {
                    return $counter->name;
                })
                ->addColumn('date', function($counter) {
                    return $counter->date;
                })
                ->addColumn('tokens_count', function($counter) {
                    return $counter->tokens_count;
                })
                ->addColumn('action', function($counter) {
                    return '<a href="' . route('admin.detailedcounterreport', $counter->name) . '"><button class="btn btn-info">Detailed</button></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function counterReportExcel(){
        return Excel::download(new CounterReport, 'counter_report.xlsx');
    }

    public function detailedCounterReport($counter){

        $counter = Counter::where('name', $counter)->first();

        $tokenCount =  DB::table('counter_token')
            ->where('counter_id', $counter->id)
            ->count();

        return view('admin.report.counter.detailedreport', compact('counter', 'tokenCount'));

    }

    public function getDetailedCounterReport(Request $request, $counter){
        $counter = Counter::where('name', $counter)->first();

        $tokensWithDate = DB::table('tokens')
                ->join('counter_token', 'tokens.id', '=', 'counter_token.token_id')
                ->where('counter_token.counter_id', $counter->id)
                ->select('tokens.date', 'tokens.token_number', 'tokens.name','counter_token.counter_id', 'counter_token.token_id')
                ->orderBy('tokens.date', 'desc')
                ->orderBy('tokens.token_number', 'desc')
                ->get();

        if($request->ajax()){
            return DataTables::of($tokensWithDate)
                ->addIndexColumn()
                ->addColumn('date', function($token) {
                    return $token->date;
                })
                ->addColumn('token_number', function($token) {
                    return $token->token_number;
                })
                ->addColumn('name', function($token) {
                    return $token->name;
                })
                ->addColumn('counter', function($token) use ($counter) {
                    return $counter->name;
                })
                ->make(true);
        }
    }

    public function detailedCounterReportExcel($counterName){
        return Excel::download(new DetailedCounterReport($counterName), $counterName.'_report.xlsx');
    }
}
