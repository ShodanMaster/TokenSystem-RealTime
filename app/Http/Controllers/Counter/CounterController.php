<?php

namespace App\Http\Controllers\Counter;

use App\Events\Counter\CounterGet;
use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Token;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CounterController extends Controller
{
    public function index(){
        return view('counter.index');
    }

    public function getToken($id){

        try {
            $tokens = Token::whereDate('date', today())->get();
            $totalTokens = $tokens->count();
            $trueTokens = $tokens->where('status', true)->count();
            $falseTokens = $tokens->where('status', false)->count();

            // dd($totalTokens == 0, $trueTokens <= $totalTokens );
            if ($totalTokens == 0 || $trueTokens >= $totalTokens) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No tokens available',
                ], 404);
            }

            $counter = Counter::find(decrypt($id));
            $token = $tokens->where('status', false)->first();

            if (!$token) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No available tokens for today',
                ], 404);
            }

            $pivot = $counter->tokens()->where('token_id', $token->id)->first();

            if (!$pivot) {
                $counter->tokens()->attach($token->id);
                $pivot = $counter->tokens()->where('token_id', $token->id)->first();
            }

            $token->update(['status' => true]);
            $pivot->pivot->update(['last_went' => $token->token_number]);

            $tokens = Token::whereDate('date', today())->get();
            $totalTokens = $tokens->count();
            $trueTokens = $tokens->where('status', true)->count();
            // dd($totalTokens.' - ' .$trueTokens . ' = '. $totalTokens - $trueTokens);
            // dd('TokensLeft'.$totalTokens - $trueTokens);
            $tokenLeft = $totalTokens - $trueTokens;

            event(new CounterGet($tokenLeft, $pivot->pivot->last_went));
            // dd($pivot->pivot);
            return response()->json([
                'status' => 200,
                'message' => 'Token Retrieved',
                'data' => [
                    'total' => $totalTokens,
                    'last_went' => $pivot->pivot->last_went,
                    'token_left' => $tokenLeft,
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
        $lastWent = DB::table('counter_token')->whereDate('created_at', Carbon::today())->where('counter_id', Auth::guard('counter')->user()->id)->latest()->first();
        // dd($lastWent);
        $tokensLeft = $totalTokens - $trueTokens;

        return response()->json([
            'status' => 200,
            'message' => 'Tokens Added',
            'data' => [
                'total' => $totalTokens ?? 0,
                'token_number' => $lastWent->last_went ?? 0,
                'total_left' => $tokensLeft,
            ]
        ]);

    }

}
