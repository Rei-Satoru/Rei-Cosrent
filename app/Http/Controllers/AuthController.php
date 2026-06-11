<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProfileContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Show unified login form
    public function showLogin()
    {
        // If already logged in, redirect appropriately
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        if (session('user_logged_in')) {
            return redirect()->route('home');
        }

        $defaultLoginType = request()->query('login_type');
        if (!in_array($defaultLoginType, ['admin', 'user'], true)) {
            $defaultLoginType = request()->routeIs('admin.login') ? 'admin' : 'user';
        }

        return view('auth.login', [
            'defaultLoginType' => $defaultLoginType,
        ]);
    }

    // Process login
    public function login(Request $request)
    {
        $request->validate([
            // unified login supports either email (current form) or username (legacy admin form)
            'email' => 'required_without:username|string',
            'username' => 'required_without:email|string',
            'password' => 'required|string',
            'context' => 'nullable|in:admin,user',
        ]);

        $identifier = trim((string) $request->input('email', $request->input('username')));
        $password = (string) $request->input('password');

        $backRoute = ($request->routeIs('admin.authenticate') || $request->input('context') === 'admin')
            ? 'admin.login'
            : 'login';

        // Determine whether this request should be handled as an admin login.
        // - Legacy endpoint /admin/authenticate forces admin flow
        // - Unified form auto-detects admin if identifier equals 'admin'
        $isAdminAttempt = $request->routeIs('admin.authenticate') || ($identifier === 'admin');

        if ($isAdminAttempt) {
            $admin_username = "admin";

            $profile = null;
            $admin_password = null;
            try {
                $profile = ProfileContact::find(1);
                if ($profile && isset($profile->password) && trim($profile->password) !== '') {
                    $admin_password = trim($profile->password);
                } else {
                    return redirect()->route($backRoute)->with('error', 'Password admin belum disetel. Hubungi pengelola.');
                }
            } catch (\Exception $e) {
                \Log::warning('Could not read admin password from admin table: ' . $e->getMessage());
                return redirect()->route($backRoute)->with('error', 'Gagal memeriksa password admin.');
            }

            $adminLoginOk = false;
            $shouldUpgradePasswordHash = false;

            // Support hashed passwords (preferred) and auto-upgrade legacy plaintext values.
            if (password_verify($password, $admin_password)) {
                $adminLoginOk = true;
                try {
                    if (Hash::needsRehash($admin_password)) {
                        $shouldUpgradePasswordHash = true;
                    }
                } catch (\Throwable $e) {
                    $shouldUpgradePasswordHash = true;
                }
            } elseif (hash_equals($admin_password, $password)) {
                $adminLoginOk = true;
                $shouldUpgradePasswordHash = true;
            }

            if ($identifier === $admin_username && $adminLoginOk) {
                if ($profile && $shouldUpgradePasswordHash) {
                    try {
                        $profile->password = Hash::make($password);
                        $profile->save();
                    } catch (\Illuminate\Database\QueryException $e) {
                        \Log::warning('Failed upgrading admin password hash (admin.password too short?): ' . $e->getMessage());
                    }
                }
                session([
                    'admin_logged_in' => true,
                    'admin_name' => $admin_username,
                    'admin_profile_photo' => $profile->photo ?? null
                ]);
                return redirect()->route('admin.profile')->with('success', 'Selamat datang, Admin!');
            } else {
                // Jika user sebenarnya mencoba login sebagai user dengan username/email "admin",
                // tetap tampilkan pesan error yang jelas.
                return redirect()->route($backRoute)->with('error', 'Username atau password admin salah!');
            }
        }

        // User login (database) - support login dengan email, username, atau nick_name
            // User login (database) - support login dengan email, username, atau nick_name
            $user = User::where('email', $identifier)
                ->orWhere('username', $identifier)
                ->orWhere('nick_name', $identifier)
                ->first();

            if (!$user) {
                return redirect()->route($backRoute)->with('error', 'User tidak ditemukan!');
            }

            if (!Hash::check($password, $user->password)) {
                return redirect()->route($backRoute)->with('error', 'Password salah!');
            }

            // Login successful
            session([
                'user_logged_in' => true,
                'user_id' => $user->id,
                'user_name' => $user->username,
                'user_email' => $user->email,
                'user_gambar_profil' => $user->gambar_profil
            ]);

            return redirect()->route('home')->with('success', 'Selamat datang, ' . ($user->nick_name ?: $user->username) . '!');
    }

    // Show register form
    public function showRegister()
    {
        // If already logged in, redirect
        if (session('admin_logged_in')) {
            return redirect()->route('admin.profile');
        }
        if (session('user_logged_in')) {
            return redirect()->route('home');
        }

        return view('auth.register');
    }

    // Process registration
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users,username|lowercase|no_spaces',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh user lain. Silakan pilih yang lain.',
            'username.lowercase' => 'Username hanya boleh menggunakan huruf kecil.',
            'username.no_spaces' => 'Username tidak boleh mengandung spasi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('register')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = User::create([
                'username' => strtolower($request->input('username')),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login.');
        } catch (\Exception $e) {
            return redirect()->route('register')
                ->with('error', 'Terjadi kesalahan saat mendaftar: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Logout (for both admin and user)
    public function logout()
    {
        $isAdmin = session('admin_logged_in');
        
        // Clear all session data
        session()->flush();
        
        $message = $isAdmin ? 'Admin berhasil logout.' : 'User berhasil logout.';
        
        return redirect()->route('home')->with('logout_message', $message);
    }

    // Redirect to Google OAuth
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle Google OAuth callback
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists by google_id
            $user = User::where('google_id', $googleUser->getId())->first();
            
            if (!$user) {
                // Check if user exists by email
                $user = User::where('email', $googleUser->getEmail())->first();
                
                if ($user) {
                    // Link Google account to existing user
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                } else {
                    // Create new user
                    $username = $this->generateUniqueUsername($googleUser->getName());
                    
                    $user = User::create([
                        'username' => $username,
                        'nick_name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'password' => null, // No password for OAuth users
                    ]);
                }
            }
            
            // Login the user
            session([
                'user_logged_in' => true,
                'user_id' => $user->id,
                'user_name' => $user->username,
                'user_email' => $user->email,
                'user_gambar_profil' => $user->gambar_profil ?? $user->avatar
            ]);
            
            return redirect()->route('home')->with('success', 'Selamat datang, ' . ($user->nick_name ?: $user->username) . '!');
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }
    }
    
    // Generate unique username from name
    private function generateUniqueUsername($name)
    {
        $baseUsername = strtolower(str_replace(' ', '_', $name));
        $baseUsername = preg_replace('/[^a-z0-9_]/', '', $baseUsername);
        
        $username = $baseUsername;
        $counter = 1;
        
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }
        
        return $username;
    }
}
