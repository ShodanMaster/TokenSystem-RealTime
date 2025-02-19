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
        $reports = Token::latest()->get();
        return view('admin.report.index', compact('reports'));
    }

    public function detailedReport($id){
        // dd($id);
        try{
            $token = Token::whereId(decrypt($id))->first();
            if ($token) {

                $date = $token->date;

                $startOfDay = Carbon::parse($date)->startOfDay();
                $endOfDay = Carbon::parse($date)->endOfDay();

                $counters = CounterToken::whereBetween('created_at', [$startOfDay, $endOfDay])
                          ->latest()
                          ->get();

                return view('admin.report.detailedreport', compact('token', 'counters'));
            } else {
                return redirect()->back()->with('error', 'Details Not Found');
            }
        }catch(Exception $e){
            return redirect()->back()->with('error', 'Something Went Wrong! '.$e->getMessage());
        }
    }
}
