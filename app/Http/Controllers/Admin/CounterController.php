<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CounterController extends Controller
{
    public function index(){
        // $counters = Counter::all();
        return view('admin.counter.index');
    }

    public function getCounters(Request $request){
        $counters = Counter::select('id', 'name', 'closed');

        if($request->ajax()){
            return DataTables::of($counters)
                ->addIndexColumn()
                ->addColumn('action', function($counter){
                    $btnClass = $counter->closed ? 'btn-danger' : 'btn-success';
                    $statusText = $counter->closed ? 'CLOSED' : 'OPEN';
                    return '<span class="btn btn-sm ' . $btnClass . ' rounded"
                                  id="statusButton"
                                  data-closed="' . $counter->closed . '"
                                  data-counter-id="' . encrypt($counter->id) . '">  <!-- Store counter ID here -->
                                ' . $statusText . '
                              </span>';
                })
                ->make(true);
        }
    }

    public function createCounter(){
        // dd("inside");
        try{
            Counter::create([
                'name' => $this->counterName(),
                'password' => bcrypt('counter123'),
            ]);
            return response()->json([
                'status' => 200,
                'message' => 'Counter Created Successfully!',
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong! '.$e->getMessage(),
            ]);
        }
    }

    public function updateStatus(Request $request){
        try{

            // dd($request->all());
            $counterId = decrypt($request->counter_id);
            $closed = $request->closed;

            // Find the counter based on ID
            $counter = Counter::find($counterId);

            if (!$counter) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Counter not found!'
                ]);
            }

            $counter->closed = $closed;
            $counter->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Status updated successfully!'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong! '.$e->getMessage(),
            ]);
        }
    }

    private function counterName(){
        $lastCounter = Counter::orderBy('id', 'desc')->first();

        $nextNumber = $lastCounter ? (substr($lastCounter->name, -1) + 1) : 1;
        return 'Counter-' . $nextNumber;
    }
}
