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
        'is_approved',
        'phone',
        'address',
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

    public function kycDocuments()
    {
        return $this->hasMany(KycDocument::class);
    }

    public function getKycStatusAttribute(): array
    {
        return KycDocument::getUserKycStatus($this->id);
    }

    public function isKycVerified(): bool
    {
        return $this->kyc_status['overall_status'] === 'verified';
    }

    public function hasKycDocumentType(string $type): bool
    {
        return $this->kycDocuments()->where('document_type', $type)->exists();
    }

    public function canSell(): bool
    {
        return $this->role === 'seller' && $this->isKycVerified() && $this->is_approved;
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
