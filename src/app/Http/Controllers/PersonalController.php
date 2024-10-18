<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonalController extends Controller
{
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
