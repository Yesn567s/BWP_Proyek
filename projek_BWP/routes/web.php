<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dummy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Public Routes - No Authentication Required
Route::get('/login', function () {
    return view('login');
})->name("login");

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Admin Routes - Require Authentication AND Admin Role
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function (){
        return view ('adminPage');
    })->name('admin');

    Route::view('/admin/{any}', 'adminPage')->where('any', '.*');
});

// User Routes - Require Authentication AND Regular User Role
Route::middleware(['auth', 'user'])->group(function () {
    // Homepage
    Route::get('/', [Dummy::class, "index"])->name("index");

    // User Data Page
    // Return simple JSON about current session user for client-side auth checks
    Route::get('/current-user', function () {
        return response()->json([
            'authenticated' => session()->has('user_id'),
            'user_id' => session('user_id') ?? null,
            'user_name' => session('user_name') ?? null,
            'user_role' => session('user_role') ?? null,
        ]);
    });

    // Return user profile data as JSON
    Route::get('/api/profile', function () {
        $user = DB::table('users')
            ->select(
                'name',
                'email',
                'phone_number',
                'points',
                'profile_picture',
                'member_start'
            )
            ->where('user_id', session('user_id'))
            ->first();

        return response()->json($user);
    });

    // Handle profile picture upload
    Route::post('/api/profile/avatar', function (Request $request) {
        $request->validate([
            'avatar' => 'required|image|max:2048', // 2MB
        ]);

        $file = $request->file('avatar');

        // Generate unique filename
        $filename = uniqid('avatar_') . '.' . $file->getClientOriginalExtension();

        // Save to storage/app/public/avatars
        $path = $file->storeAs('avatars', $filename, 'public');

        // Save path to DB
        DB::table('users')
            ->where('user_id', session('user_id'))
            ->update([
                'profile_picture' => 'storage/' . $path
            ]);

        return response()->json([
            'status' => 'success',
            'path' => 'storage/' . $path
        ]);
    });

    // Update personal information
    Route::post('/api/profile/personal', function (Request $request) {
        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        // Prevent duplicate email (except self)
        $emailExists = DB::table('users')
            ->where('email', $request->email)
            ->where('user_id', '!=', session('user_id'))
            ->exists();

        if ($emailExists) {
            return response()->json(['error' => 'Email already in use'], 409);
        }

        DB::table('users')
            ->where('user_id', session('user_id'))
            ->update([
                'name'         => $request->name,
                'email'        => $request->email,
                'phone_number' => $request->phone,
            ]);

        // Update session name
        session(['user_name' => $request->name]);

        return response()->json(['status' => 'success']);
    });

    // Payment Methods
    // Return user's payment methods as JSON
    Route::get('/api/payment-methods', function () {
        $methods = DB::table('payment_method')
            ->where('user_id', session('user_id'))
            ->orderBy('id', 'desc')
            ->get()
            ->map(function ($m) {
                return [
                    'id'      => $m->id,
                    'type'    => ucfirst($m->card_type),
                    'last4'   => substr($m->card_number, -4),
                    'expiry'  => date('m/y', strtotime($m->expire_date)),
                    'isDefault' => false // optional, extend later
                ];
            });

        return response()->json($methods);
    });

    // Add a new payment method
    Route::post('/api/payment-methods', function (Request $request) {
        $request->validate([
            'type'       => 'required|in:Visa,Mastercard',
            'name'       => 'required|string|max:100',
            'cardNumber' => 'required|string|max:25|unique:payment_method,card_number',
            'expiry'     => 'required|string', // MM/YY
        ]);

        // Convert MM/YY → DATE
        [$month, $year] = explode('/', $request->expiry);
        $expireDate = "20{$year}-{$month}-01";

        DB::table('payment_method')->insert([
            'user_id'     => session('user_id'),
            'card_type'   => strtolower($request->type),
            'name'        => $request->name,
            'card_number' => $request->cardNumber,
            'expire_date' => $expireDate,
        ]);

        return response()->json(['status' => 'success']);
    });

    // Delete a payment method
    Route::delete('/api/payment-methods/{id}', function ($id) {
        DB::table('payment_method')
            ->where('id', $id)
            ->where('user_id', session('user_id'))
            ->delete();

        return response()->json(['status' => 'success']);
    });

    // Return user language code as JSON
    Route::get('/api/language', function () {
        $user = DB::table('users')
            ->select(
                'language_code'
            )
            ->where('user_id', session('user_id'))
            ->first();
        return response()->json($user);
    });

    // Update user language preference
    Route::post('/api/language', function (Request $request) {
        $request->validate([
            'language_code' => 'required|in:en,id,jp,fr'
        ]);

        DB::table('users')
            ->where('user_id', session('user_id'))
            ->update([
                'language_code' => $request->language_code
            ]);

        return response()->json(['status' => 'success']);
    });

    // Return user security settings as JSON
    Route::get('/api/security', function () {
        $user = DB::table('users')
            ->select('two_factor_auth')
            ->where('user_id', session('user_id'))
            ->first();

        return response()->json($user);
    });

    // Update user security settings
    Route::post('/api/security', function (Request $request) {
        $user = DB::table('users')
            ->where('user_id', session('user_id'))
            ->first();

        $errors = [];

        // If user tries to change password
        if ($request->newPassword) {

            // Current password check
            if ($user->password !== $request->currentPassword) {
                $errors['currentPassword'] = 'Current password is incorrect';
            }

            // Minimum length
            if (strlen($request->newPassword) < 3) {
                $errors['newPassword'] = 'Password must be at least 3 characters';
            }

            // Duplicate password
            if ($request->newPassword === $request->currentPassword) {
                $errors['newPassword'] = 'New password must be different from current password';
            }

            // Confirm password mismatch
            if ($request->newPassword !== $request->confirmPassword) {
                $errors['confirmPassword'] = 'New password does not match confirmation';
            }

            if (!empty($errors)) {
                return response()->json(['errors' => $errors], 422);
            }

            // Update password
            DB::table('users')
                ->where('user_id', session('user_id'))
                ->update([
                    'password' => $request->newPassword // hash in production
                ]);
        }

        // Two factor auth toggle
        DB::table('users')
            ->where('user_id', session('user_id'))
            ->update([
                'two_factor_auth' => $request->twoFactorEnabled ? 1 : 0
            ]);

        return response()->json(['status' => 'success']);
    });

    // Notification Settings Routes
    Route::get('/api/notifications', function () {
        try {
            $userId = session('user_id');

            // Default settings structure
            $settings = [
                'upcoming_movies' => ['email' => false, 'push' => false],
                'new_products' => ['email' => false, 'push' => false],
                'events' => ['email' => false, 'push' => false],
                'payments' => ['email' => false, 'push' => false]
            ];

            // Get all notification types with their details
            $types = DB::table('notification_type')->get();
            Log::info('Notification types found: ' . $types->count());
            foreach ($types as $t) {
                $code = $t->CODE ?? $t->code ?? null;
                Log::info("  Type: id={$t->id}, code={$code}, title={$t->title}");
            }
            
            if (!$types || $types->count() === 0) {
                Log::warning('No notification types found, returning defaults');
                return response()->json($settings);
            }

            // Fetch user's settings from database
            $userSettings = DB::table('user_notification_setting')
                ->where('user_id', $userId)
                ->get();
            Log::info('User notification settings found: ' . $userSettings->count());
            foreach ($userSettings as $us) {
                Log::info("  User setting: notification_type_id={$us->notification_type_id}, email={$us->email_enabled}, push={$us->push_enabled}");
            }

            // Map settings by type code
            foreach ($types as $type) {
                // Use CODE (uppercase) as that's the actual column name
                $typeCode = $type->CODE ?? $type->code ?? null;
                
                // Find if this user has a setting for this type
                $userSetting = $userSettings->firstWhere('notification_type_id', $type->id);
                
                if ($userSetting) {
                    $settings[$typeCode] = [
                        'email' => (bool) $userSetting->email_enabled,
                        'push' => (bool) $userSetting->push_enabled
                    ];
                    Log::info("Mapped {$typeCode}: email={$userSetting->email_enabled}, push={$userSetting->push_enabled}");
                } else {
                    Log::info("No setting found for {$typeCode} (type_id={$type->id})");
                }
            }

            Log::info('Final settings: ' . json_encode($settings));
            return response()->json($settings);
        } catch (\Exception $e) {
            Log::error('Error fetching notification settings: ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            // Return default settings even if database fails
            return response()->json([
                'upcoming_movies' => ['email' => false, 'push' => false],
                'new_products' => ['email' => false, 'push' => false],
                'events' => ['email' => false, 'push' => false],
                'payments' => ['email' => false, 'push' => false]
            ]);
        }
    });

    Route::post('/api/notifications', function (Request $request) {
        try {
            $userId = session('user_id');
            $settings = $request->all();

            // Get all notification types - use CODE (uppercase) as key
            $types = DB::table('notification_type')->get();
            
            // Create a map keyed by CODE
            $typesMap = [];
            foreach ($types as $t) {
                $code = $t->CODE ?? $t->code ?? null;
                if ($code) {
                    $typesMap[$code] = $t;
                }
            }

            if (count($typesMap) === 0) {
                Log::error('No notification types found');
                return response()->json(['error' => 'No notification types found'], 400);
            }

            $updatedCount = 0;

            foreach ($settings as $code => $preferences) {
                // Skip if this is not a valid notification type code
                if (!isset($typesMap[$code])) {
                    Log::warning("Code {$code} not found in notification types");
                    continue;
                }

                $type = $typesMap[$code];
                $typeId = $type->id;

                // Extract email and push preferences
                $emailEnabled = false;
                $pushEnabled = false;

                if (is_array($preferences)) {
                    $emailEnabled = (bool) ($preferences['email'] ?? false);
                    $pushEnabled = (bool) ($preferences['push'] ?? false);
                }

                Log::info("Saving {$code}: email={$emailEnabled}, push={$pushEnabled}");

                // Check if record exists
                $existing = DB::table('user_notification_setting')
                    ->where('user_id', $userId)
                    ->where('notification_type_id', $typeId)
                    ->first();

                if ($existing) {
                    // Update existing record
                    $updated = DB::table('user_notification_setting')
                        ->where('id', $existing->id)
                        ->update([
                            'email_enabled' => $emailEnabled ? 1 : 0,
                            'push_enabled' => $pushEnabled ? 1 : 0,
                            'updated_at' => DB::raw('NOW()')
                        ]);
                    Log::info("Updated {$code}: rows affected = {$updated}");
                    $updatedCount += $updated;
                } else {
                    // Insert new record
                    $inserted = DB::table('user_notification_setting')->insert([
                        'user_id' => $userId,
                        'notification_type_id' => $typeId,
                        'email_enabled' => $emailEnabled ? 1 : 0,
                        'push_enabled' => $pushEnabled ? 1 : 0,
                        'created_at' => DB::raw('NOW()'),
                        'updated_at' => DB::raw('NOW()')
                    ]);
                    Log::info("Inserted {$code}: success = {$inserted}");
                    if ($inserted) $updatedCount++;
                }
            }

            Log::info("Total updated/inserted: {$updatedCount}");
            return response()->json([
                'status' => 'success',
                'message' => 'Notification settings saved successfully',
                'updated' => $updatedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to save settings',
                'message' => $e->getMessage()
            ], 500);
        }
    });

    // User Vouchers Route
    Route::get('/api/user-vouchers', [VoucherController::class, 'getUserVouchers']);

    // Logout route
    Route::post('/logout', function () {
        session()->flush();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ]);
    });

    // Catch-all route for SPA client-side routes (prevents 404 on page refresh)
    Route::get('/{any}', [Dummy::class, 'index'])->where('any', '.*');
});