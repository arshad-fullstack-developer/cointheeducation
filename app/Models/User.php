<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'role_id',
        'status',
        'custom_permissions',
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
        'custom_permissions' => 'array',
    ];
    }

    /**
     * Get the role that belongs to the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if the user has a specific permission.
     */
    public function hasPermission(string $permissionName): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // If user has custom permissions, ONLY check custom permissions (restrictive mode)
        if ($this->custom_permissions && count($this->custom_permissions) > 0) {
            // Check for exact permission match
            if (in_array($permissionName, $this->custom_permissions)) {
                return true;
            }
            
            // Check for module-level permission (e.g., if permission is 'dashboard.view' and user has 'dashboard')
            $moduleName = explode('.', $permissionName)[0];
            if (in_array($moduleName, $this->custom_permissions)) {
                return true;
            }
            
            return false;
        }

        // If no custom permissions, fall back to role permissions
        if (!$this->role) {
            return false;
        }

        return $this->role->hasPermission($permissionName);
    }

    /**
     * Check if the user has any permission from a module.
     */
    public function hasModulePermission(string $module): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        // If user has custom permissions, ONLY check custom permissions (restrictive mode)
        if ($this->custom_permissions && count($this->custom_permissions) > 0) {
            foreach ($this->custom_permissions as $permission) {
                if (str_starts_with($permission, $module . '.')) {
                    return true;
                }
            }
            return false; // No custom permissions for this module
        }

        // If no custom permissions, fall back to role permissions
        if (!$this->role) {
            return false;
        }

        return $this->role->hasModulePermission($module);
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    /**
     * Check if the user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if the user is a manager.
     */
    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    /**
     * Check if the user is staff.
     */
    public function isStaff(): bool
    {
        return $this->hasRole('staff');
    }

    /**
     * Check if the user is a viewer.
     */
    public function isViewer(): bool
    {
        return $this->hasRole('viewer');
    }
}
