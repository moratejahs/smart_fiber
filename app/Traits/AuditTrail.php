<?php

namespace App\Traits;

use App\Models\AuditLog;

trait AuditTrail
{
    public static function bootAuditTrail()
    {
        static::created(function ($model) {
            self::logActivity('created', $model);
        });

        static::updated(function ($model) {
            self::logActivity('updated', $model);
        });

        static::deleted(function ($model) {
            self::logActivity('deleted', $model);
        });
    }

    protected static function logActivity($action, $model)
    {
        $user = auth()->user();
        if (!$user) return;

        AuditLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'module' => class_basename($model),
            'description' => self::generateUserFriendlyDescription($user, $action, $model),
            'old_values' => $action !== 'created' ? $model->getOriginal() : null,
            'new_values' => $model->getAttributes(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    protected static function generateUserFriendlyDescription($user, $action, $model)
    {
        $modelName = class_basename($model);
        $userName = $user->name;
        if ($modelName === 'User') {
            $targetName = $model->name ?? '';
            $barangay = $model->barangay ?? '';
            if ($action === 'created') {
                return "$userName added a new user named $targetName in $barangay.";
            } elseif ($action === 'updated') {
                return "$userName updated the user $targetName in $barangay.";
            } elseif ($action === 'deleted') {
                return "$userName deleted the user $targetName from $barangay.";
            }
        }
        // Add more model-specific descriptions as needed
        return "$userName $action a $modelName.";
    }
}
