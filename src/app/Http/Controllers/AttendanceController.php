<?php

namespace App\Http\Controllers;

use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function startWork(Request $request)
    {
        $work = new Work;
        $work->users_id = Auth::id();
        $work->work_in = now();
        $work->date = now()->toDateString();
        $work->save();

        return redirect()->back();
    }

    public function endWork(Request $request)
    {
        $work = Work::where('users_id', Auth::id())
                    ->whereNull('work_out')
                    ->orderBy('work_in', 'desc')
                    ->first();

        $work->work_out = now();
        $work->save();

        return redirect()->back();
    }

    public function showAtte()
    {
        $date = now()->toDateString();

        return $this->getAtteData($date);
    }

    public function showByDate($date)
    {
        return $this->getAtteData($date);
    }

    private function getAtteData($date)
    {
        $attendances = DB::table('works')
            ->join('users', 'works.users_id', '=', 'users.id')
            ->leftJoin('rests', 'works.id', '=', 'rests.works_id')
            ->select(
                'users.id',
                'users.name',
                'works.date',
                'works.work_in',
                'works.work_out',
                DB::raw('SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, rests.rest_start, rests.rest_end))) as total_rest_time'),
                DB::raw('SEC_TO_TIME(
                    CASE
                    WHEN works.work_out < works.work_in THEN
                        TIMESTAMPDIFF(SECOND, works.work_in, works.work_out) + 86400
                    ELSE
                        TIMESTAMPDIFF(SECOND, works.work_in, works.work_out)
                    END
                    - IFNULL(SUM(TIMESTAMPDIFF(SECOND, rests.rest_start, rests.rest_end)), 0)
            ) as total_work_time')
            )
            ->whereDate('works.date', $date)
            ->groupBy('works.id', 'users.name', 'works.date', 'works.work_in', 'works.work_out')
            ->orderBy('works.work_in', 'asc')
            ->paginate(5);

        $previousDate = DB::table('works')
            ->where('date', '<', $date)
            ->orderBy('date', 'desc')
            ->value(DB::raw('DATE(date)'));

        $nextDate = DB::table('works')
            ->where('date', '>', $date)
            ->orderBy('date', 'asc')
            ->value(DB::raw('DATE(date)'));

        return view('attendance', compact('attendances', 'date', 'previousDate', 'nextDate'));
    }
}