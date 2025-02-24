<?php

namespace App\Http\Controllers\Admin;

use App\Events\Admin\AdminEvent;
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
        $lastWent = DB::table('counter_token')->whereDate('created_at', Carbon::today())->latest()->first();
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
