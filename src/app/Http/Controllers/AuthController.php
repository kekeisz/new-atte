<?php

namespace App\Http\Controllers;

use App\Models\Rest;
use App\Models\User;
use App\Models\Work;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        $user = User::where('id', Auth::id())
                    ->value('name');

        $work = Work::where('users_id', Auth::id())
                    ->whereNull('work_out')
                    ->orderBy('work_in', 'desc')
                    ->first();

        $status = 'not_working';

        if ($work) {
            $rest = Rest::where('works_id', $work->id)
                        ->whereNull('rest_end')
                        ->orderBy('rest_start', 'desc')
                        ->first();

            if ($rest) {
                $status = 'resting';
            } else {
                $status = 'working';
            }
        }

        return view('index', compact('status', 'user'));
    }
}
