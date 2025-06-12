<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'address',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function isAdmin(): bool
    {
        return $this->roles->contains('key', 'admin');
    }

    public function isManager(): bool
    {
        return $this->roles->contains('key', 'manager');
    }

    public function hasRole(string $role): bool
    {
        return $this->roles->contains('key', $role);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function scopeFilter(Builder $query, Request $request)
    {
        $search = $request->search;
        $roles = Role::query()
            ->whereIn('key', ['admin', 'manager'])
            ->pluck('id')
            ->toArray();

        return $query
            ->whereDoesntHave('roles', function ($query) use ($roles) {
                $query->whereIn('roles.id', $roles);
            })->with('roles')
            ->when($search, function (Builder $query) use ($search) {
                $query->where('name', 'like', "%" . $search . "%")
                    ->orWhere('phone', 'like', "%" . $search . "%")
                    ->orWhere('email', 'like', "%" . $search . "%")
                    ->orWhere('address', 'like', "%" . $search . "%");
            });
    }

    public static function normalizePhoneNumbers($phone)
    {
        if (!$phone) {
            return null;
        }
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (!str_starts_with($phone, '992')) {
            $phone = '992' . $phone;
        }
        return '+' . $phone;
    }
}
