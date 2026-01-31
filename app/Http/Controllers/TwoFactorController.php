<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\Verify2FAMiddleware;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TwoFactorController extends Controller
{
    public function index()
    {
            /** @var \App\Models\User $user */

        $user = Auth::user();
        $code = rand(100000, 999999);
        $user->two_factor_code = $code;
        $user->save();

        $message = "Dear {$user->name},\n\n";
        $message .= "Your Two-Factor Authentication Code is: {$code}\n\n";
        $message .= "Please enter this code to complete your login process.\n";
        $message .= "This code will expire in 10 minutes.\n\n";
        $message .= "If you did not request this code, please ignore this email.\n\n";
        $message .= "Thank you,\n";
        $message .= config('app.name');

        Mail::raw($message, function ($email) use ($user) {
            $email->to($user->email)
                  ->subject('Your Two-Factor Authentication Code');
        });

        return view('auth.two-factor');
    }
        public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|integer',
        ]);
            /** @var \App\Models\User $user */

        $user = Auth::user();

        if ($request->code == $user->two_factor_code) {
            // Mark as authenticated in the session
            session(['two_factor_authenticated' => true]);

            $user->two_factor_code = null;
            $user->save();

            // Redirect to the intended URL or a default
            return redirect()->intended('/dashboard')->with('toast', 'Login successful.');
        }

        return back()->withErrors(['code' => 'The provided code is incorrect.']);
    }
}