<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class ActivityRepository
{
    public const TABLE = 'activities';

    public function storeActivity(int $userId, string $type)
    {
        return DB::table(self::TABLE)
            ->insert([
                'user_id' => $userId,
                'type' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    }
}
