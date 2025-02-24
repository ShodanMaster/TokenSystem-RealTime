<?php

namespace App\Http\Controllers\Counter;

use App\Exports\Counter\CounterReport;
use App\Exports\Counter\DetailedReport;
use App\Http\Controllers\Controller;
use App\Models\Token;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function index(){
        return view('counter.report.index');
    }

    public function getReport(Request $request){
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

        if ($request->ajax()) {
            return DataTables::of($reports)
                ->addIndexColumn()
                ->addColumn('action', function ($report) {
                    return '<a href="' . route('counter.detailedreport', $report->date) . '" class="btn btn-info">Detailed</a>';
                })
                ->make(true);
        }
    }

    public function getReportExcel(){
        return Excel::download(new CounterReport, Auth::guard('counter')->user()->name.'_report.xlsx');
    }

    public function detailedReport($date){
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

    public function getDetailedReport(Request $request, $date){
        $startOfDay = Carbon::parse($date)->startOfDay();
        $endOfDay = Carbon::parse($date)->endOfDay();

        $tokens = Token::whereHas('counters', function ($query) {
            $query->where('counter_id', Auth::guard('counter')->user()->id);
        })->whereBetween('created_at', [$startOfDay, $endOfDay])
                    ->latest()
                    ->get();

        if ($request->ajax()) {
            return DataTables::of($tokens)
                ->addIndexColumn()
                ->addColumn('name', function ($token) {
                    return $token->name;
                })
                ->addColumn('token_number', function ($token) {
                    return $token->token_number;
                })
                ->make(true);
        }
    }

    public function getDetailedReportExcel($date){

        return Excel::download(new DetailedReport($date ), Auth::guard('counter')->user()->name.'_'.$date.'_detailed_report.xlsx');
    }
}
