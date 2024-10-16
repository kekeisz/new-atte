<?php

namespace App\Http\Controllers;

use App\Models\Rest;
use App\Models\User;
use App\Models\Work;
use Carbon\Carbon;
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

    public function showAtte($date = null)
    {
        // URLパラメータの日付がない場合は今日の日付を使用
        $date = $date ?? now()->toDateString();

        // 勤怠データを取得し、指定された日付のデータのみを取得
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
                DB::raw('SEC_TO_TIME
                    (TIMESTAMPDIFF(SECOND, works.work_in, works.work_out)
                    - IFNULL(SUM(TIMESTAMPDIFF(SECOND, rests.rest_start, rests.rest_end)), 0)
                ) as total_work_time')
            )
            ->whereDate('works.date', $date)
            ->groupBy('works.id', 'users.name', 'works.date', 'works.work_in', 'works.work_out')
            ->orderBy('works.work_in', 'asc')
            ->paginate(5);

        // 直前の日付を取得
        $previousDate = DB::table('works')
            ->where('date', '<', $date)
            ->orderBy('date', 'desc')
            ->value(DB::raw('DATE(date)'));

        // 直後の日付を取得
        $nextDate = DB::table('works')
            ->where('date', '>', $date)
            ->orderBy('date', 'asc')
            ->value(DB::raw('DATE(date)'));

        // ビューに勤怠データと日付を渡す
        return view('attendance', compact('attendances', 'date', 'previousDate', 'nextDate'));
    }

    public function showUser()
    {
        $users = User::all();

        return view('user', compact('users'));
    }

    public function showPersonal($userId = null)
    {
        $currentUserId = $userId ?? DB::table('users')->orderBy('id', 'asc')->value('id');

        $currentUser = DB::table('users')->where('id', $currentUserId)->first();

        $attendances = DB::table('works')
            ->join('users', 'works.users_id', '=', 'users.id')
            ->leftJoin('rests', 'works.id', '=', 'rests.works_id')
            ->select(
                'users.name',
                'works.date',
                'works.work_in',
                'works.work_out',
                DB::raw('SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, rests.rest_start, rests.rest_end))) as total_rest_time'),
                DB::raw('SEC_TO_TIME
                    (TIMESTAMPDIFF(SECOND, works.work_in, works.work_out)
                    - IFNULL(SUM(TIMESTAMPDIFF(SECOND, rests.rest_start, rests.rest_end)), 0)
                ) as total_work_time')
            )
            ->where('users.id', $currentUserId)
            ->groupBy('works.id', 'users.name', 'works.date', 'works.work_in', 'works.work_out')
            ->orderBy('works.date', 'asc')
            ->paginate(5);


        $previousUser = DB::table('users')
            ->where('id', '<', $currentUserId)
            ->orderBy('id', 'desc')
            ->value('id');

        if(!$previousUser) {
            $previousUser = DB::table('users')
            ->orderBy('id', 'desc')
            ->value('id');
        }

        $nextUser = DB::table('users')
            ->where('id', '>', $currentUserId)
            ->orderBy('id', 'asc')
            ->value('id');

        if(!$nextUser) {
            $nextUser = DB::table('users')
            ->orderBy('id', 'asc')
            ->value('id');
        }

        return view('personal', compact('attendances', 'currentUserId', 'currentUser', 'previousUser', 'nextUser'));
    }
}