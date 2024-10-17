<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function sendEmail() {
        return view('auth.login');
    }

    public function verify(EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect('/');
    }
}
