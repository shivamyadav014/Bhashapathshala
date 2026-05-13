<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }
        return view('auth.login');
    }

    public function handleLogin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        Auth::login($user, $request->filled('remember'));

        return $this->redirectToDashboard();
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectToDashboard();
        }
        return view('auth.register');
    }

    public function handleRegister(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|in:student,instructor',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'student',
        ]);

        Auth::login($user);

        return $this->redirectToDashboard();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Logged out successfully');
    }

    public function profile()
    {
        return view('profile.show');
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
            'profile_image' => 'nullable|url',
        ]);

        Auth::user()->update($validated);

        return redirect()->route('profile')->with('success', 'Profile updated successfully');
    }

    public function settings()
    {
        return view('profile.settings');
    }


    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password is incorrect'],
            ]);
        }

        if ($validated['password']) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('settings')->with('success', 'Settings updated successfully');
    }

    // Forgot/Reset Password Methods
    public function showForgotPassword()
    {
        return view('auth.forgot');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'No user found with that email address.']);
        }

        // Generate token and save to DB (simple implementation)
        $token = bin2hex(random_bytes(32));
        $user->reset_token = $token;
        $user->reset_token_created_at = now();
        $user->save();

        // Send real email
        \Mail::to($user->email)->send(new \App\Mail\ResetPasswordMail($token));

        return back()->with('status', 'If your email exists in our system, a password reset link has been sent.');
    }

    public function showResetPassword($token)
    {
        return view('auth.reset', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required',
        ]);

        $user = User::where('email', $request->email)
            ->where('reset_token', $request->token)
            ->first();

        if (!$user || !$user->reset_token || !$user->reset_token_created_at || now()->diffInMinutes($user->reset_token_created_at) > 60) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        $user->password = Hash::make($request->password);
        $user->reset_token = null;
        $user->reset_token_created_at = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Password reset successful. You can now log in.');
    }

    private function redirectToDashboard()
    {
        $user = Auth::user();

        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'instructor' => redirect()->route('instructor.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default => redirect('/'),
        };
    }
}
