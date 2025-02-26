<?php

namespace App\Http\Controllers\Admin;

use App\Events\Admin\AdminEvent;
use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Token;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(){
        $totalTokens = Token::count();
        $firstTokenDate = Token::min('created_at');
        $days = $firstTokenDate ? now()->diffInDays($firstTokenDate) + 1 : 1;
        $tokenAverage = round($totalTokens / $days, 2);

        $totalTokens = Token::whereDate('date', today())->count();

        $mostVisitedCounter = Counter::select('counters.name')
            ->join('counter_token', 'counters.id', '=', 'counter_token.counter_id')
            ->groupBy('counters.id', 'counters.name')
            ->orderByRaw('COUNT(counter_token.id) DESC')
            ->limit(1)
            ->value('name');
        return view('admin.index', compact('tokenAverage', 'mostVisitedCounter', 'totalTokens'));
    }

    public function issue(){
        $date = Carbon::today()->format('F j, Y');
        return view('admin.issue', compact('date'));
    }

    public function addToken(Request $request){

        $request->validate([
            'name' => 'required|string',
        ]);

        try {
            $lastToken = Token::whereDate('date', today())->latest()->first();
            $trueTokens = Token::whereDate('date', today())->where('status', true)->count();
            $totalTokens = Token::whereDate('date', today())->count();

            $tokenNumber = $lastToken ? $lastToken->token_number + 1 : 1;

            $token = Token::create([
                'name' => $request->name,
                'token_number' => $tokenNumber,
                'date' => today(),
            ]);

            $totalTokens = Token::whereDate('date', today())->count();
            $tokensLeft = $totalTokens - $trueTokens;
            $lastWent = DB::table('counter_token')->whereDate('created_at', today())->latest()->value('last_went') ?? 0;

            event(new AdminEvent($totalTokens, $tokensLeft));

            return response()->json([
                'status' => 200,
                'message' => 'Token Added',
                'data' => [
                    'total' => $totalTokens,
                    'token_number' => $lastWent,
                    'total_left' => $tokensLeft,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function windowLoad(){

        $trueTokens = Token::whereDate('date', today())->where('status', true)->count();
        $totalTokens = Token::whereDate('date', today())->count();
        $tokensLeft = $totalTokens - $trueTokens;

        $lastFiveTokens = DB::table('counter_token')
            ->whereDate('created_at', today())
            ->latest()
            ->limit(5)
            ->get();

        $lastFiveData = $lastFiveTokens->map(function ($token) {
            return [
                'token_number' => $token->last_went ?? 0,
                'counter' => Counter::whereId($token->counter_id)->value('name') ?? 'No Counter',
                'name' => Token::whereId($token->token_id)->value('name') ?? 'No Name',
            ];
        });

        return response()->json([
            'status' => 200,
            'message' => 'Tokens Loaded',
            'data' => [
                'total' => $totalTokens,
                'total_left' => $tokensLeft,
                'last_five' => $lastFiveData,
            ],
        ]);
    }


}
