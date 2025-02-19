<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Token;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(){
        $reports = Token::latest()->get();
        return view('admin.report.index', compact('reports'));
    }
}
