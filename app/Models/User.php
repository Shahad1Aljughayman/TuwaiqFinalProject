<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role; 

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
    public static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Check if the user has a role and if not, assign the default 'patient' role
            if (!$user->role) {
                $role = Role::where('name', 'patient')->first();  // Ensure you have the 'patient' role in your roles table
                if ($role) {
                    $user->role()->associate($role);  // Associate the 'patient' role to the user
                    $user->save();
                }
            }
        });
    }

    /**
     * The role relationship, assuming the user has a foreign key 'role_id'.
     */
    public function role()
    {
        return $this->belongsTo(Role::class); // Assuming the Role model has a 'name' field and 'id' is used as foreign key
    }
}
