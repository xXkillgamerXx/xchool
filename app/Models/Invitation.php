<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 
        'name', 
        'first_name', 
        'last_name', 
        'role_id', 
        'token', 
        'expires_at', 
        'invited_by', 
        'status'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * Get the role for this invitation.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the user who sent the invitation.
     */
    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Generate a unique token for the invitation.
     */
    public static function generateToken(): string
    {
        return Str::random(64);
    }

    /**
     * Check if the invitation is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if the invitation is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending' && !$this->isExpired();
    }

    /**
     * Mark invitation as accepted.
     */
    public function markAsAccepted(): void
    {
        $this->update(['status' => 'accepted']);
    }

    /**
     * Mark invitation as expired.
     */
    public function markAsExpired(): void
    {
        $this->update(['status' => 'expired']);
    }
}
