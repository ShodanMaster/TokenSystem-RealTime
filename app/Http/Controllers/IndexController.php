<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index(){
        $counters = Counter::where('closed', false)->get();
        return view('index', compact('counters'));
    }

    public function windowLoad(){

        $counters = Counter::where('closed', false)->get();
        $totalTokens = Token::whereDate('date', today())->count();

        $latestTokens = DB::table('counter_token')
            ->join('counters', 'counter_token.counter_id', '=', 'counters.id')
            ->select('counter_token.counter_id', 'counter_token.last_went', 'counters.name')
            ->whereIn('counter_token.counter_id', $counters->pluck('id'))
            ->whereDate('counter_token.created_at', Carbon::today())
            ->orderBy('counter_token.created_at', 'desc')
            ->get()
            ->groupBy('counter_id')
            ->map(function ($group) {
                return $group->first();
            });

        $latestTokensArray = $latestTokens->map(function ($t) {
            return [
                'counter' => $t->name,
                'token' => $t->last_went
            ];
        })->values();

        return response()->json([
            'status' => 200,
            'message' => 'Data Found',
            'data' => $latestTokensArray,
            'totalTokens' => $totalTokens,
        ]);
    }

}
