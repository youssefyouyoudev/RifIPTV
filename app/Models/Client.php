<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'assigned_admin_id',
        'phone',
        'preferred_payment_method',
        'preferred_bank',
        'onboarding_status',
        'city',
        'support_notes',
        'last_contacted_at',
        'support_started_at',
        'setup_tutorial_sent_at',
        'credentials_sent_at',
        'completed_at',
        'iptv_username',
        'iptv_password',
    ];

    protected function casts(): array
    {
        return [
            'last_contacted_at' => 'datetime',
            'support_started_at' => 'datetime',
            'setup_tutorial_sent_at' => 'datetime',
            'credentials_sent_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_admin_id');
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
