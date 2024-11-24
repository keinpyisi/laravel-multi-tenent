<?php

namespace App\Http\Controllers\Tenents;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function showLoginForm() {
        return view('tenents.auth.login');
    }

    public function login(Request $request) {
        try {
            // Validate input
            $request->validate([
                'login_id' => 'required|string',
                'password' => 'required|string',
            ]);

            // Attempt login using user ID and password
            $credentials = $request->only('login_id', 'password');
            if (Auth::attempt($credentials)) {
                dd("SUCCESS");
                // If login successful, redirect to dashboard or home
                return redirect()->route('tenant.client.index');
            }
            // If authentication fails, log the error and return back
            return back()->withErrors(['login_id' => 'Invalid credentials']);
        } catch (Exception $ex) {
            log_message('Error occurred during login : ', ['exception' => $ex->getMessage()]);
        }
    }

    public function logout() {
        Auth::logout();
        return redirect('tenant.users.login');
    }
}
