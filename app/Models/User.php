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
        'division_id',
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

    //operator
    public function canManageDivisionProduction($divisionId): bool
    {
        return $this->role === 'division_production_manager' && $this->divisions()->where('division_id', $divisionId)->exists();
    }

    public function canManagePoProduction()
    {
        return in_array($this->role, ['ppc', 'admin']);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    // Helpers
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPPC()
    {
        return $this->role === 'ppc';
    }

    public function isSupervisor()
    {
        return $this->role === 'supervisor produksi';
    }

    public function isOperator()
    {
        return $this->role === 'operator';
    }

    public function isForeman()
    {
        return $this->role === 'foreman';
    }

    public function getRoleBadgeColorAttribute()
    {
        return match ($this->role) {
            'admin' => 'danger',
            'ppc' => 'primary',
            'supervisor produksi' => 'warning',
            'operator' => 'info',
            default => 'secondary'
        };
    }

    public function canViewBatch()
    {
        return in_array($this->role, ['admin', 'ppc', 'supervisor produksi', 'operator']);
    }

    public function canCreateBatch()
    {
        return in_array($this->role, ['admin', 'ppc']);
    }

    public function canEditBatch()
    {
        return in_array($this->role, ['admin', 'ppc']);
    }

    public function canDeleteBatch()
    {
        return $this->role === 'admin';
    }

    public function canViewBatchTimeline()
    {
        return in_array($this->role, ['admin', 'ppc', 'supervisor']);
    }


    public function canViewPO()
    {
        return in_array($this->role, ['admin', 'ppc', 'supervisor produksi']);
    }

    public function canCreatePO()
    {
        return in_array($this->role, ['admin', 'ppc']);
    }

    public function canEditPO()
    {
        return in_array($this->role, ['admin', 'ppc']);
    }

    public function canViewWipTracking()
    {
        return in_array($this->role, ['admin', 'ppc', 'supervisor']);
    }

    public function canUpdateWipTracking()
    {
        return in_array($this->role, ['admin', 'supervisor', 'operator']);
    }

    public function canViewProductionProcess()
    {
        return in_array($this->role, ['admin', 'ppc', 'supervisor', 'operator']);
    }

    public function canUpdateProductionProcess()
    {
        return in_array($this->role, ['admin', 'supervisor', 'operator']);
    }

    public function canManageMaster()
    {
        return $this->role === 'admin';
    }

    public function canViewMaster()
    {
        return in_array($this->role, ['admin', 'ppc', 'supervisor']);
    }

    public function canManageUsers()
    {
        return $this->role === 'admin';
    }
}
