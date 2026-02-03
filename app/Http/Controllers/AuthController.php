<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Security\SecurityTools;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $input = SecurityTools::sanitizeArray($request->all());

        $validator = Validator::make($input, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'department' => 'required|string|in:' . implode(',', array_keys(User::DEPARTMENTS)),
            'password' => 'required|string|min:8|confirmed',
            'terms' => 'accepted',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Create user with default role and fixed position
        $user = User::create([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? null,
            'department' => $input['department'],
            'position' => 'Employee', // Always employee
            'password' => Hash::make($input['password']),
            'role' => 'employee', // Default role
            'status' => 'active', // Optional default status
        ]);

        auth()->login($user);

        return redirect()->route('login')->with('success', 'Registration successful!');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $input = SecurityTools::sanitizeArray($request->only('email', 'password'));

        $isAdminLogin = $input['email'] === 'admin' || $input['email'] === 'admin@system.local';

        if ($isAdminLogin) {
            if (!SecurityTools::safeEquals('V4u!t#27_r3sQ', $input['password'])) {
                return back()->withErrors([
                    'email' => 'Invalid admin credentials.',
                ])->onlyInput('email');
            }

            // Ensure an admin user exists and log them in without needing an email from the form
            $adminUser = User::firstOrCreate(
                ['email' => 'admin@system.local'],
                [
                    'first_name' => 'System',
                    'last_name' => 'Administrator',
                    'phone' => null,
                    'department' => array_key_first(User::DEPARTMENTS),
                    'position' => 'Administrator',
                    'password' => Hash::make('V4u!t#27_r3sQ'),
                    'role' => 'admin',
                    'status' => 'active',
                ]
            );

            Auth::login($adminUser);
            $request->session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        // Regular user login using email and password from database
        $credentials = [
            'email' => $input['email'],
            'password' => $input['password'],
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // If user has 2FA enabled, redirect to two-factor verification page
            if ($user->two_factor_enabled ?? false) {
                // You can store a flag to indicate 2FA is pending
                session(['2fa:user:id' => $user->id]);
                Auth::logout(); // Logout until they verify the code
                return redirect()->route('two-factor.index');
            }

            // Otherwise, go directly to dashboard
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
