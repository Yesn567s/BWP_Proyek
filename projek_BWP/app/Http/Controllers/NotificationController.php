<?php

namespace App\Http\Controllers;

use App\Models\UserNotificationSetting;
use App\Models\NotificationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Get all notification settings for the authenticated user
     * Returns settings mapped by notification type code
     */
    public function getSettings()
    {
        // Get user ID from session (Laravel session-based auth)
        $userId = session('user_id');
        
        if (!$userId) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'User not authenticated'
            ], 401);
        }

        // Get all notification types with user's settings
        $types = NotificationType::with(['userSettings' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->get();

        // Format response
        $settings = [];
        foreach ($types as $type) {
            $userSetting = $type->userSettings->first();
            
            $settings[$type->code] = [
                'id' => $userSetting->id ?? null,
                'notification_type_id' => $type->id,
                'email' => (bool) ($userSetting->email_enabled ?? false),
                'push' => (bool) ($userSetting->push_enabled ?? false)
            ];
        }

        return response()->json($settings);
    }

    /**
     * Save notification settings for the authenticated user
     */
    public function saveSettings(Request $request)
    {
        // Get user ID from session (Laravel session-based auth)
        $userId = session('user_id');

        if (!$userId) {
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'User not authenticated'
            ], 401);
        }

        $settings = $request->all();
        $errors = [];

        // Get all notification types to know which ones should exist
        $types = NotificationType::all()->keyBy('code');

        foreach ($settings as $code => $preferences) {
            // Skip if code doesn't match a notification type
            if (!isset($types[$code])) {
                continue;
            }

            $notificationTypeId = $types[$code]->id;
            $emailEnabled = (bool) ($preferences['email'] ?? false);
            $pushEnabled = (bool) ($preferences['push'] ?? false);

            try {
                // Use updateOrCreate to handle both insert and update
                UserNotificationSetting::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'notification_type_id' => $notificationTypeId
                    ],
                    [
                        'email_enabled' => $emailEnabled ? 1 : 0,
                        'push_enabled' => $pushEnabled ? 1 : 0
                    ]
                );
            } catch (\Exception $e) {
                $errors[$code] = 'Failed to update setting: ' . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'status' => 'partial',
                'errors' => $errors
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Notification settings saved successfully'
        ]);
    }
}
