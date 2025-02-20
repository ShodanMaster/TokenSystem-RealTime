<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Token;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }

    public function addToken(Request $request){
        // dd($request->all());
        $request->validate([
            'name' => 'required|string'
        ]);
        try{
            $lastToken = Token::whereDate('date', today())->latest()->first();
            $trueTokens = Token::whereDate('date', today())->where('status', true)->count();
            // dd($pendingTokens);
            $totalTokens = Token::whereDate('date', today())->count();
            // dd('trueTokens: '. $trueTokens, 'totalTokens: '.$totalTokens);
            // dd($tokensLeft);
            if (!$lastToken) {
                $tokenNumber = 0;
            } else {
                $tokenNumber = $lastToken->token_number;
            }

            $token = Token::create([
                'name' => $request->name,
                'token_number' => $tokenNumber + 1
            ]);


            $totalTokens = Token::whereDate('date', today())->count();
            $tokensLeft = $totalTokens - $trueTokens;
            // dd($tokensLeft);
            $lastWent = DB::table('counter_token')->latest()->first();
            // dd($lastWent);
            return response()->json([
                'status' => 200,
                'message' => 'Tokens Added',
                'data' => [
                    'total' => $totalTokens ?? 0,
                    'token_number' => $lastWent->last_went ?? 0,
                    'total_left' => $tokensLeft,
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
