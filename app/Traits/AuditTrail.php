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
            'description' => "User {$user->name} {$action} a {class_basename($model)}",
            'old_values' => $action !== 'created' ? $model->getOriginal() : null,
            'new_values' => $model->getAttributes(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
