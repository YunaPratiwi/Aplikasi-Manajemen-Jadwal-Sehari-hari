<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'theme_preference' => 'light',
            'email_notifications' => true,
            'push_notifications' => true,
            'notification_settings' => [
                'task_reminders' => true,
                'due_date_alerts' => true,
                'collaboration_invites' => true,
                'weekly_summary' => true,
            ],
        ]);

        // Create default categories for new user
        Category::createDefaultCategories($user);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Akun berhasil dibuat! Selamat datang di TodoList.');
    }

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Update last login
            $user = Auth::user();
            if ($user) {
                User::where('id', $user->id)->update(['last_login_at' => now()]);
            }

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang kembali!');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Email atau password tidak valid.'])
            ->withInput();
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Show the forgot password form.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password request.
     */
    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Here you would typically send a password reset email
        // For now, we'll just return a success message
        return redirect()->back()
            ->with('success', 'Link reset password telah dikirim ke email Anda.');
    }

    /**
     * Show user profile.
     */
    public function profile()
    {
        return view('auth.profile', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'theme_preference' => ['required', 'in:light,dark'],
            'email_notifications' => ['boolean'],
            'push_notifications' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'theme_preference' => $request->theme_preference,
            'email_notifications' => $request->boolean('email_notifications'),
            'push_notifications' => $request->boolean('push_notifications'),
        ];

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = time() . '_' . $avatar->getClientOriginalName();
            $avatar->move(public_path('uploads/avatars'), $filename);
            $data['avatar'] = 'uploads/avatars/' . $filename;
        }

        // Handle notification settings
        if ($request->has('notification_settings')) {
            $data['notification_settings'] = [
                'task_reminders' => $request->boolean('notification_settings.task_reminders'),
                'due_date_alerts' => $request->boolean('notification_settings.due_date_alerts'),
                'collaboration_invites' => $request->boolean('notification_settings.collaboration_invites'),
                'weekly_summary' => $request->boolean('notification_settings.weekly_summary'),
            ];
        }

        $user->update($data);

        return redirect()->back()
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        $user = Auth::user();
        /** @var \App\Models\User $user */

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password saat ini tidak benar.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()
            ->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Update notification settings.
     */
    public function updateNotificationSettings(Request $request)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        
        $user->update([
            'email_notifications' => $request->boolean('email_notifications'),
            'push_notifications' => $request->boolean('push_notifications'),
            'notification_settings' => [
                'task_reminders' => $request->boolean('task_reminders'),
                'due_date_alerts' => $request->boolean('due_date_alerts'),
                'collaboration_invites' => $request->boolean('collaboration_invites'),
                'weekly_summary' => $request->boolean('weekly_summary'),
            ],
        ]);

        return response()->json(['success' => true, 'message' => 'Pengaturan notifikasi berhasil diperbarui.']);
    }

    /**
     * Update privacy settings.
     */
    public function updatePrivacySettings(Request $request)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        
        // Add privacy settings fields to user model if needed
        $privacySettings = [
            'profile_visibility' => $request->input('profile_visibility', 'private'),
            'task_sharing' => $request->boolean('task_sharing'),
            'activity_tracking' => $request->boolean('activity_tracking'),
            'data_collection' => $request->boolean('data_collection'),
        ];

        // Store in notification_settings for now, or create a separate privacy_settings field
        $currentSettings = $user->notification_settings ?? [];
        $currentSettings['privacy'] = $privacySettings;
        
        $user->update(['notification_settings' => $currentSettings]);

        return response()->json(['success' => true, 'message' => 'Pengaturan privasi berhasil diperbarui.']);
    }

    /**
     * Update account settings.
     */
    public function updateAccountSettings(Request $request)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'timezone' => ['nullable', 'string'],
            'language' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Store additional settings in notification_settings
        $currentSettings = $user->notification_settings ?? [];
        $currentSettings['account'] = [
            'timezone' => $request->input('timezone'),
            'language' => $request->input('language', 'id'),
        ];
        
        $user->update(['notification_settings' => $currentSettings]);

        return response()->json(['success' => true, 'message' => 'Pengaturan akun berhasil diperbarui.']);
    }

    /**
     * Show API tokens.
     */
    public function apiTokens()
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        $tokens = $user->tokens;

        return response()->json([
            'tokens' => $tokens->map(function ($token) {
                return [
                    'id' => $token->id,
                    'name' => $token->name,
                    'abilities' => $token->abilities,
                    'last_used_at' => $token->last_used_at,
                    'created_at' => $token->created_at,
                ];
            })
        ]);
    }

    /**
     * Create API token.
     */
    public function createApiToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'abilities' => ['array'],
            'abilities.*' => ['string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        /** @var \App\Models\User $user */
        $abilities = $request->input('abilities', ['*']);
        
        $token = $user->createToken($request->name, $abilities);

        return response()->json([
            'success' => true,
            'message' => 'Token API berhasil dibuat.',
            'token' => [
                'id' => $token->accessToken->id,
                'name' => $token->accessToken->name,
                'token' => $token->plainTextToken,
                'abilities' => $token->accessToken->abilities,
            ]
        ]);
    }
    
    /**
     * Delete API token.
     */
    public function deleteApiToken($tokenId)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        
        $token = $user->tokens()->find($tokenId);
        
        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Token tidak ditemukan.'], 404);
        }
        
        $token->delete();
        
        return response()->json(['success' => true, 'message' => 'Token API berhasil dihapus.']);
    }
    
    /**
     * Show user profile page.
     */
    public function show()
    {
        return view('profile.show', [
            'user' => Auth::user(),
        ]);
    }
    
    /**
     * Update user profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->back()
            ->with('success', 'Profile berhasil diperbarui.');
    }
    
    /**
     * Delete user account.
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */
        
        // Optional: Verify password before deletion
        if ($request->has('password')) {
            if (!Hash::check($request->password, $user->password)) {
                return redirect()->back()
                    ->withErrors(['password' => 'Password tidak benar.']);
            }
        }
        
        // Delete user's tasks and categories first
        $user->tasks()->delete();
        $user->categories()->delete();
        
        // Logout user
        Auth::logout();
        
        // Delete user account
        $user->delete();
        
        return redirect()->route('login')
            ->with('success', 'Akun Anda telah berhasil dihapus.');
    }
}
