<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_verified',
        'is_onboarded',
        'subscription_status',
        'subscription_plan',
        'trial_ends_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
            'is_onboarded' => 'boolean',
            'trial_ends_at' => 'datetime',
        ];
    }

    public function studentProfile()
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function startupProfile()
    {
        return $this->hasOne(StartupProfile::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isStartup(): bool
    {
        return $this->role === 'startup';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /* ─── Subscription Helpers ─── */

    public function activeSubscription()
    {
        return $this->subscriptions()
            ->whereIn('status', ['active', 'trial'])
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>=', now());
            })
            ->latest()
            ->first();
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription() !== null;
    }

    public function isStudentPro(): bool
    {
        if (!$this->isStudent()) {
            return false;
        }
        
        $activeSub = $this->activeSubscription();
        return $activeSub && $activeSub->plan_type === 'student_pro';
    }

    public function isStartupGrowth(): bool
    {
        if (!$this->isStartup()) {
            return false;
        }

        $activeSub = $this->activeSubscription();
        return $activeSub && $activeSub->plan_type === 'startup_growth';
    }
}
