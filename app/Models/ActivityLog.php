<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'description', 'loggable_type', 'loggable_id',
        'ip_address', 'user_agent', 'properties',
    ];

    protected $casts = ['properties' => 'array'];

    public function user() { return $this->belongsTo(User::class); }

    public static function log(string $action, string $description, $model = null, array $properties = []): self
    {
        return static::create([
            'user_id'       => auth()->id(),
            'action'        => $action,
            'description'   => $description,
            'loggable_type' => $model ? get_class($model) : null,
            'loggable_id'   => $model?->id,
            'ip_address'    => request()->ip(),
            'user_agent'    => request()->userAgent(),
            'properties'    => $properties ?: null,
        ]);
    }
}
