<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Friendship extends Model
{
    protected $fillable = ['user_id', 'friend_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function friend(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

    protected static function booted(): void
    {
        static::created(function (Friendship $friendship): void {
            DB::table('friendships')->insertOrIgnore([
                'user_id' => $friendship->friend_id,
                'friend_id' => $friendship->user_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        static::deleted(function (Friendship $friendship): void {
            DB::table('friendships')
                ->where('user_id', $friendship->friend_id)
                ->where('friend_id', $friendship->user_id)
                ->delete();
        });
    }
}
