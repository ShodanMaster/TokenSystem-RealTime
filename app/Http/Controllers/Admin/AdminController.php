<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Token;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

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

            if (!$lastToken) {
                $totalToken = 0;
                $tokenLeft = 0;
            } else {
                $totalToken = $lastToken->total_token;
                $tokenLeft = $lastToken->token_left;
            }

            $token = Token::create([
                'name' => $request->name,
                'total_token' => $totalToken + 1, // Increment the total_token value
                'token_left' => $tokenLeft + 1,  // Increment the token_left value
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Tokens Added',
                'data' => [
                    'total' => $token->total_token,
                    'last_went' => $token->last_went ?? 0,
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
