<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'role',
        'department',
        'position',
        'status',
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
        ];
    }

    /**
     * Available user roles
     */
    public const ROLES = [
        'admin' => 'Administrator',
        'manager' => 'Manager',
        'supervisor' => 'Supervisor',
        'employee' => 'Employee',
    ];

    /**
     * Available departments
     */
    public const DEPARTMENTS = [
        'Operations' => 'Operations',
        'Customer Service' => 'Customer Service',
        'Management' => 'Management',
        'Sales' => 'Sales',
        'HR' => 'Human Resources',
        'IT' => 'Information Technology',
    ];

    /**
     * Available user statuses
     */
    public const STATUSES = [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'terminated' => 'Terminated',
    ];

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Get the user's initials.
     */
    public function getInitialsAttribute(): string
    {
        return strtoupper(substr($this->first_name, 0, 1) . substr($this->last_name, 0, 1));
    }

    /**
     * Get the shift
    /**
     * Check if user has a specific role.
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a manager.
     */
    public function isManager()
    {
        return in_array($this->role, ['admin', 'manager']);
    }

    /**
     * Check if user can manage shifts.
     */
   
    /**
     * Get role label.
     */
    public function getRoleLabelAttribute()
    {
        return self::ROLES[$this->role] ?? ucfirst($this->role);
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUSES[$this->status ?? 'active'] ?? ucfirst($this->status);
    }
}
