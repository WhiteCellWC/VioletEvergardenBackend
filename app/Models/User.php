<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends AuthBaseModel
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    // Table Column Start
    public const table = 'users';

    public const id = 'id';

    public const name = 'name';

    public const email = 'email';

    public const password = 'password';

    public const dateOfBirth = 'date_of_birth';

    public const gender = 'gender';

    public const profileImage = 'profile_image';

    public const bio = 'bio';

    public const version = 'version';

    public const createdBy = 'created_by';

    public const updatedBy = 'updated_by';
    // Table Column End

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'date_of_birth',
        'gender',
        'profile_image',
        'bio',
        'version',
        'created_by',
        'updated_by'
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

    #region Relations
    public function notifications()
    {
        return $this->hasMany(Notification::class, Notification::userId);
    }

    public function settings()
    {
        return $this->hasMany(UserSetting::class, UserSetting::userId);
    }

    public function waxSealTypes()
    {
        return $this->hasMany(WaxSealType::class, WaxSealType::userId);
    }

    public function letters()
    {
        return $this->hasMany(Letter::class, Letter::userId);
    }

    public function recipients()
    {
        return $this->hasMany(Recipient::class, Recipient::userId);
    }
    #endregion
}
