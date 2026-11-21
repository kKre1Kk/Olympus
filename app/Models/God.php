<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class God extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'badge',
        'sphere',
        'description',
        'symbols',
        'parents',
        'siblings',
        'spouse',
        'children',
        'temple',
        'myth',
        'image_path',
        'recorded_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::updating(function (God $god) {
            $user = auth()->user();

            abort_unless(
                $user && ($user->is_admin || $user->id === $god->user_id),
                403
            );
        });

        static::deleting(function (God $god) {
            $user = auth()->user();

            abort_unless(
                $user && ($user->is_admin || $user->id === $god->user_id),
                403
            );
        });

        static::restoring(function () {
            abort_unless(auth()->user()?->is_admin, 403);
        });

        static::forceDeleting(function () {
            abort_unless(auth()->user()?->is_admin, 403);
        });
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => trim($value)
        );
    }

    protected function recordedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->format('d.m.Y') : null,
            set: fn ($value) => $value ? Carbon::parse($value)->format('Y-m-d') : null
        );
    }
}
