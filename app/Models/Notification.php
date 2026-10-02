<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id', 'title', 'message', 'type',
        'related_type', 'related_id', 'is_read', 'read_at',
    ];

    protected $casts = ['is_read' => 'boolean', 'read_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }

    public function markAsRead()
    {
        $this->update(['is_read' => true, 'read_at' => now()]);
    }

    public static function send(int $userId, string $title, string $message, string $type = 'info', $related = null): self
    {
        return static::create([
            'user_id'      => $userId,
            'title'        => $title,
            'message'      => $message,
            'type'         => $type,
            'related_type' => $related ? get_class($related) : null,
            'related_id'   => $related?->id,
        ]);
    }
}
