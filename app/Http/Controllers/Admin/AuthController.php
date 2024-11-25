<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    protected $redirectTo = 'backend/admin/login';
    public function showLoginForm() {
        Auth::logout();
        return view('admin.auth.login');
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
            DB::statement("SET search_path TO base_tenants");
            if (Auth::attempt($credentials)) {
                // If login successful, redirect to dashboard or home
                return redirect()->route('admin.tenants.index');
            }
            // If authentication fails, log the error and return back
            return back()->withErrors(['login_id' => 'Invalid credentials']);
        } catch (Exception $ex) {
            log_message('Error occurred during login : ', ['exception' => $ex->getMessage()]);
        } finally {
            DB::statement("SET search_path TO base_tenants");
        }
    }

    public function logout() {
        Auth::logout();
        return redirect('admin.users.login');
    }
}
