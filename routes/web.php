<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CollaborationController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Guest routes (redirect if authenticated)
Route::middleware('guest')->group(function () {
    // Home route - redirect to login if not authenticated
    Route::get('/', function () {
        return redirect()->route('login');
    })->name('home');
    
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
        
        Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
        
        // Forgot password
        Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');
        Route::get('/reset-password/{token}', function () {
            return view('auth.reset-password');
        })->name('password.reset');
    });
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [AuthController::class, 'profile'])->name('show');
        Route::put('/', [AuthController::class, 'update'])->name('update');
        Route::put('/password', [AuthController::class, 'updatePassword'])->name('password.update');
        Route::delete('/', [AuthController::class, 'destroy'])->name('destroy');
        Route::post('/2fa/enable', [AuthController::class, 'enable2FA'])->name('2fa.enable');
        Route::post('/2fa/disable', [AuthController::class, 'disable2FA'])->name('2fa.disable');
    });
    
    // Task management
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('index');
        Route::get('/create', [TaskController::class, 'create'])->name('create');
        Route::post('/', [TaskController::class, 'store'])->name('store');
        Route::get('/{task}', [TaskController::class, 'show'])->name('show');
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
        Route::put('/{task}', [TaskController::class, 'update'])->name('update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
        
        // Task actions
        Route::post('/{task}/toggle', [TaskController::class, 'toggle'])->name('toggle');
        Route::post('/{task}/archive', [TaskController::class, 'archive'])->name('archive');
        Route::post('/{task}/unarchive', [TaskController::class, 'unarchive'])->name('unarchive');
        Route::post('/{task}/duplicate', [TaskController::class, 'duplicate'])->name('duplicate');
        Route::post('/{task}/assign', [TaskController::class, 'assign'])->name('assign');
        Route::post('/{task}/unassign', [TaskController::class, 'unassign'])->name('unassign');
        Route::post('/{task}/add-comment', [TaskController::class, 'addComment'])->name('add-comment');
        Route::post('/{task}/add-attachment', [TaskController::class, 'addAttachment'])->name('add-attachment');
        Route::delete('/{task}/attachment/{attachment}', [TaskController::class, 'deleteAttachment'])->name('delete-attachment');
        
        // Task time tracking
        Route::post('/{task}/time-tracking/start', [TaskController::class, 'startTimeTracking'])->name('time-tracking.start');
        Route::post('/{task}/time-tracking/stop', [TaskController::class, 'stopTimeTracking'])->name('time-tracking.stop');
        Route::post('/{task}/time-tracking/log', [TaskController::class, 'logTime'])->name('time-tracking.log');
        Route::get('/{task}/time-tracking', [TaskController::class, 'getTimeTracking'])->name('time-tracking.get');
        
        // Bulk actions
        Route::post('/bulk-action', [TaskController::class, 'bulkAction'])->name('bulk-action');
        Route::post('/reorder', [TaskController::class, 'reorder'])->name('reorder');
    });
    
    // Category management
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        
        // Category actions
        Route::post('/{category}/toggle-active', [CategoryController::class, 'toggleActive'])->name('toggle-active');
        Route::post('/{category}/duplicate', [CategoryController::class, 'duplicate'])->name('duplicate');
        Route::post('/reorder', [CategoryController::class, 'reorder'])->name('reorder');
    });
    
    // Collaboration features
    Route::prefix('collaboration')->name('collaboration.')->group(function () {
        Route::get('/', [CollaborationController::class, 'index'])->name('index');
        
        // Task collaboration
        Route::post('/tasks/{task}/invite', [CollaborationController::class, 'invite'])->name('tasks.invite');
        Route::post('/tasks/{task}/accept', [CollaborationController::class, 'accept'])->name('tasks.accept');
        Route::post('/tasks/{task}/decline', [CollaborationController::class, 'decline'])->name('tasks.decline');
        Route::delete('/tasks/{task}/remove/{user}', [CollaborationController::class, 'remove'])->name('tasks.remove');
        Route::put('/tasks/{task}/update-role/{user}', [CollaborationController::class, 'updateRole'])->name('tasks.update-role');
        
        // Team management
        Route::get('/teams', [CollaborationController::class, 'teams'])->name('teams');
        Route::get('/teams/create', [CollaborationController::class, 'createTeam'])->name('teams.create');
        Route::post('/teams', [CollaborationController::class, 'storeTeam'])->name('teams.store');
        Route::get('/teams/{team}', [CollaborationController::class, 'showTeam'])->name('teams.show');
        Route::get('/teams/{team}/edit', [CollaborationController::class, 'editTeam'])->name('teams.edit');
        Route::put('/teams/{team}', [CollaborationController::class, 'updateTeam'])->name('teams.update');
        Route::delete('/teams/{team}', [CollaborationController::class, 'destroyTeam'])->name('teams.destroy');
    });
    
    // Export functionality
    Route::prefix('export')->name('export.')->group(function () {
        // Task exports
        Route::get('/tasks/csv', [ExportController::class, 'tasksCSV'])->name('tasks.csv');
        Route::get('/tasks/pdf', [ExportController::class, 'tasksPDF'])->name('tasks.pdf');
        Route::get('/tasks/excel', [ExportController::class, 'tasksExcel'])->name('tasks.excel');
        Route::post('/tasks/custom', [ExportController::class, 'customTasksExport'])->name('tasks.custom');
        
        // Category exports
        Route::get('/categories/csv', [ExportController::class, 'categoriesCSV'])->name('categories.csv');
        Route::get('/categories/pdf', [ExportController::class, 'categoriesPDF'])->name('categories.pdf');
        
        // Report exports
        Route::get('/reports/summary', [ExportController::class, 'summaryReport'])->name('reports.summary');
        Route::get('/reports/detailed', [ExportController::class, 'detailedReport'])->name('reports.detailed');
    });
    
    // Search functionality
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/', [SearchController::class, 'index'])->name('index');
        Route::get('/tasks', [SearchController::class, 'tasks'])->name('tasks');
        Route::get('/categories', [SearchController::class, 'categories'])->name('categories');
        Route::get('/suggestions', [SearchController::class, 'suggestions'])->name('suggestions');
        Route::get('/advanced', [SearchController::class, 'advanced'])->name('advanced');
    });
    
    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread', [NotificationController::class, 'unread'])->name('unread');
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/productivity', [ReportController::class, 'productivity'])->name('productivity');
        Route::get('/time-tracking', [ReportController::class, 'timeTracking'])->name('time-tracking');
        Route::get('/completion', [ReportController::class, 'completion'])->name('completion');
        Route::get('/team', [ReportController::class, 'team'])->name('team');
    });
    
    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', function () {
            return view('settings.index');
        })->name('index');
        
        // Theme settings
        Route::post('/theme', function () {
            $theme = request('theme', 'light');
            $user = auth()->user();
            if ($user) {
                \App\Models\User::where('id', $user->id)->update(['theme_preference' => $theme]);
            }
            return response()->json(['success' => true]);
        })->name('theme');
        
        // Notification settings
        Route::post('/notifications', [AuthController::class, 'updateNotificationSettings'])->name('notifications');
        
        // Privacy settings
        Route::post('/privacy', [AuthController::class, 'updatePrivacySettings'])->name('privacy');
        
        // Account settings
        Route::post('/account', [AuthController::class, 'updateAccountSettings'])->name('account');
        
        // API settings
        Route::get('/api/tokens', [AuthController::class, 'apiTokens'])->name('api.tokens');
        Route::post('/api/tokens', [AuthController::class, 'createApiToken'])->name('api.tokens.create');
        Route::delete('/api/tokens/{token}', [AuthController::class, 'deleteApiToken'])->name('api.tokens.delete');
    });
    
    // API routes for AJAX calls
    Route::prefix('api')->name('api.')->group(function () {
        // Task API
        Route::get('/tasks', [TaskController::class, 'apiIndex'])->name('tasks.index');
        Route::get('/tasks/{task}', [TaskController::class, 'apiShow'])->name('tasks.show');
        Route::get('/tasks/stats', [TaskController::class, 'stats'])->name('tasks.stats');
        Route::post('/tasks/{task}/time-tracking', [TaskController::class, 'updateTimeTracking'])->name('tasks.time-tracking');
        
        // Category API
        Route::get('/categories', [CategoryController::class, 'apiIndex'])->name('categories.index');
        Route::get('/categories/{category}', [CategoryController::class, 'apiShow'])->name('categories.show');
        Route::get('/categories/stats', [CategoryController::class, 'stats'])->name('categories.stats');
        
        // User API
        Route::get('/user/stats', [DashboardController::class, 'userStats'])->name('user.stats');
        Route::get('/user/preferences', [AuthController::class, 'userPreferences'])->name('user.preferences');
    });
});

// Archive routes
Route::middleware(['auth'])->prefix('archive')->name('archive.')->group(function () {
    Route::get('/tasks', [TaskController::class, 'archived'])->name('tasks');
    Route::get('/categories', [CategoryController::class, 'archived'])->name('categories');
});

// Public routes (no authentication required)
Route::prefix('public')->name('public.')->group(function () {
    Route::get('/shared/{token}', [TaskController::class, 'sharedTask'])->name('shared-task');
    Route::get('/shared/{token}/download', [TaskController::class, 'downloadSharedTask'])->name('shared-task.download');
});

// Webhook routes for external integrations
Route::prefix('webhooks')->name('webhooks.')->group(function () {
    Route::post('/github', [CollaborationController::class, 'githubWebhook'])->name('github');
    Route::post('/slack', [CollaborationController::class, 'slackWebhook'])->name('slack');
});

// Fallback route for undefined routes
Route::fallback(function () {
    if (auth()->check()) {
        return response()->view('errors.404');
    }
    return redirect()->route('login');
});
