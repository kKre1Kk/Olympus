<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => trim($value)
        );
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->format('d.m.Y H:i') : null,
            set: fn ($value) => Carbon::parse($value)->format('Y-m-d H:i:s')
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