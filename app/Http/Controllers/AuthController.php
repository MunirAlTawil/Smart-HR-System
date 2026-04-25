<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as AuthFacade;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (AuthFacade::check()) {
            return $this->redirectBasedOnRole();
        }
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (AuthFacade::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole();
        }

        throw ValidationException::withMessages([
            'email' => [__('auth.failed')],
        ]);
    }

    /**
     * Quick login as admin or user (for development/testing).
     */
    public function quickLogin(string $role)
    {
        if (!in_array($role, [User::ROLE_ADMIN, User::ROLE_USER])) {
            return redirect()->route('login')->with('error', 'Invalid role.');
        }

        $user = User::where('role', $role)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'No user found with this role. Run: php artisan db:seed');
        }

        AuthFacade::login($user, true);
        return $this->redirectBasedOnRole();
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        AuthFacade::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Redirect user based on role.
     */
    private function redirectBasedOnRole()
    {
        if (AuthFacade::user()->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }
        return redirect()->intended(route('home'));
    }
}
