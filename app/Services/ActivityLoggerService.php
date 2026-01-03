<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;

class ActivityLoggerService
{
    /**
     * Log an activity performed by a pegawai/staff.
     */
    public static function log(
        User $user,
        string $action,
        string $description,
        ?string $subjectType = null,
        ?int $subjectId = null
    ): ActivityLog {
        return ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
        ]);
    }
}
