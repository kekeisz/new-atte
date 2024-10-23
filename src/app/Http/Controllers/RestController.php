<?php

namespace App\Http\Controllers;

use App\Models\Rest;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestController extends Controller
{
    public function startRest(Request $request)
    {
        $work = Work::where('users_id', Auth::id())
                    ->whereNull('work_out')
                    ->orderBy('work_in', 'desc')
                    ->first();

        $rest = new Rest();
        $rest->works_id = $work->id;
        $rest->rest_start = now();
        $rest->save();

        return redirect()->back();
    }

    public function endRest(Request $request)
    {
        $work = Work::where('users_id', Auth::id())
                    ->whereNull('work_out')
                    ->orderBy('work_in', 'desc')
                    ->first();

        $rest = Rest::where('works_id', $work->id)
                    ->whereNull('rest_end')
                    ->orderBy('rest_start', 'desc')
                    ->first();

        $rest->rest_end = now();
        $rest->save();

        return redirect()->back();
    }
}
